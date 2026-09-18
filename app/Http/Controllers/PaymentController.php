<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with([
            'payable' => function ($morphTo) {
                $morphTo->morphWith([
                    SalesInvoice::class => ['customer:id,name'],
                    PurchaseInvoice::class => ['supplier:id,name'],
                ]);
            }
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHasMorph('payable', [SalesInvoice::class, PurchaseInvoice::class], function ($q, $type) use ($search) {
                      $q->where('invoice_number', 'like', "%{$search}%");
                      if ($type === SalesInvoice::class) {
                          $q->orWhereHas('customer', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                      } else {
                          $q->orWhereHas('supplier', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                      }
                  });
            });
        }

        if ($request->filled('type')) {
            $typeClass = $request->type === 'sales' ? SalesInvoice::class : PurchaseInvoice::class;
            $query->where('payable_type', $typeClass);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->latest('payment_date')->paginate(20)->withQueryString();
        $methods = \App\Enums\PaymentMethod::options();
        
        return view('payments.index', compact('payments', 'methods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('storePayment', [
            'payable_id' => 'required|integer',
            'payable_type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|integer',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = PaymentStatus::COMPLETED->value;

        Payment::create($validated);

        // Update paid_amount on sales invoice
        if ($validated['payable_type'] === SalesInvoice::class) {
            $invoice = SalesInvoice::find($validated['payable_id']);
            if ($invoice) {
                $invoice->paid_amount = $invoice->payments()->sum('amount');
                // Auto-update status
                if ($invoice->paid_amount >= $invoice->total) {
                    $invoice->status = \App\Enums\InvoiceStatus::PAID;
                }
                $invoice->save();
            }
        }

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }
}
