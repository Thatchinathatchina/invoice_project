<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Budget;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // Financial summary cards
        $totalSalesRevenue = SalesInvoice::sum('total');
        $totalPurchases = PurchaseInvoice::sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $totalPaymentsReceived = Payment::where('payable_type', SalesInvoice::class)->sum('amount');
        $outstandingReceivables = $totalSalesRevenue - $totalPaymentsReceived;
        $netProfit = $totalSalesRevenue - $totalPurchases - $totalExpenses;

        // Counts
        $customerCount = Customer::count();
        $supplierCount = Supplier::count();
        $pendingInvoices = SalesInvoice::where('status', InvoiceStatus::PENDING)->count();
        $overdueInvoices = SalesInvoice::where('status', InvoiceStatus::OVERDUE)->count();

        // Recent invoices
        $recentSalesInvoices = SalesInvoice::with('customer')->latest()->take(5)->get();
        $recentPurchaseInvoices = PurchaseInvoice::with('supplier')->latest()->take(5)->get();

        // Budget overview
        $budgets = Budget::where('status', 'active')->get();



        return view('dashboard', compact(
            'totalSalesRevenue',
            'totalPurchases',
            'totalExpenses',
            'totalPaymentsReceived',
            'outstandingReceivables',
            'netProfit',
            'customerCount',
            'supplierCount',
            'pendingInvoices',
            'overdueInvoices',
            'recentSalesInvoices',
            'recentPurchaseInvoices',
            'recentPurchaseInvoices',
            'budgets'
        ));
    }

}
