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

        $salesInvoices = SalesInvoice::with('customer')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        $purchaseInvoices = PurchaseInvoice::with('supplier')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        return view('reports.invoice-summary', compact(
            'salesInvoices', 'purchaseInvoices', 'startDate', 'endDate'
        ));
    }
}
