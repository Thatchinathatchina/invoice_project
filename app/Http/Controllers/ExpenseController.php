<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('budget:id,name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        if ($request->filled('budget_id')) {
            $query->where('budget_id', $request->budget_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $expenses = $query->latest('expense_date')->paginate(20)->withQueryString();
        $budgets = Budget::where('status', 'active')->get();
        return view('expenses.index', compact('expenses', 'budgets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('storeExpense', [
            'category_id' => 'required|exists:budgets,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'reference_number' => 'nullable|string|max:100',
            'payment_method' => 'required|integer',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validateWithBag('updateExpense', [
            'category_id' => 'required|exists:budgets,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'reference_number' => 'nullable|string|max:100',
            'payment_method' => 'required|integer',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
