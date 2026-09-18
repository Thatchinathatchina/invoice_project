<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $salesRevenue = SalesInvoice::whereBetween('invoice_date', [$startDate, $endDate])->sum('total');
        $purchaseCost = PurchaseInvoice::whereBetween('invoice_date', [$startDate, $endDate])->sum('total_amount');
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        $grossProfit = $salesRevenue - $purchaseCost;
        $netProfit = $grossProfit - $expenses;

        return view('reports.profit-loss', compact(
            'salesRevenue', 'purchaseCost', 'expenses',
            'grossProfit', 'netProfit', 'startDate', 'endDate'
        ));
    }

    public function budgetVsExpense(Request $request)
    {
        $budgets = Budget::with('expenses')->get()->map(function ($budget) {
            return [
                'name' => $budget->name,
                'budgeted' => (float) $budget->amount,
                'spent' => $budget->total_spent,
                'remaining' => $budget->remaining,
                'usage_percent' => $budget->usage_percent,
                'start_date' => $budget->start_date->format('Y-m-d'),
                'end_date' => $budget->end_date->format('Y-m-d'),
            ];
        });

        return view('reports.budget-vs-expense', compact('budgets'));
    }

    public function invoiceSummary(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $type = $request->input('type', 'all');

        $invoices = collect();

        if ($type === 'all' || $type === 'sales') {
            $sales = SalesInvoice::with('customer')
                ->whereBetween('invoice_date', [$startDate, $endDate])
                ->get()
                ->map(function($inv) {
                    $inv->type_label = 'Sales';
                    $inv->party_name = $inv->customer->name ?? 'N/A';
                    $inv->display_amount = $inv->total;
                    return $inv;
                });
            $invoices = $invoices->merge($sales);
        }

        if ($type === 'all' || $type === 'purchase') {
            $purchases = PurchaseInvoice::with('supplier')
                ->whereBetween('invoice_date', [$startDate, $endDate])
                ->get()
                ->map(function($inv) {
                    $inv->type_label = 'Purchase';
                    $inv->party_name = $inv->supplier->name ?? 'N/A';
                    $inv->display_amount = $inv->total_amount;
                    return $inv;
                });
            $invoices = $invoices->merge($purchases);
        }

        $invoices = $invoices->sortByDesc('invoice_date');

        return view('reports.invoice-summary', compact(
            'invoices', 'startDate', 'endDate', 'type'
        ));
    }
}
