<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class BudgetAndExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $budgets = [
            ['name' => 'Marketing Q3 2026',    'amount' => 100000.00, 'start_date' => '2026-07-01', 'end_date' => '2026-09-30', 'description' => 'Marketing and advertising budget for Q3'],
            ['name' => 'Office Supplies',       'amount' => 25000.00,  'start_date' => '2026-09-01', 'end_date' => '2026-09-30', 'description' => 'Monthly office supplies budget'],
            ['name' => 'IT Infrastructure',     'amount' => 200000.00, 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'description' => 'Annual IT infrastructure budget'],
            ['name' => 'Employee Training',     'amount' => 50000.00,  'start_date' => '2026-09-01', 'end_date' => '2026-12-31', 'description' => 'Employee training and development'],
        ];
        $budgetModels = [];
        foreach ($budgets as $b) {
            $budgetModels[] = Budget::create($b);
        }

        $expenses = [
            ['category_id' => $budgetModels[0]->id, 'amount' => 35000.00, 'expense_date' => '2026-07-15', 'description' => 'Google Ads campaign',           'reference_number' => 'EXP-001', 'payment_method' => PaymentMethod::BANK_TRANSFER, 'status' => 'approved'],
            ['category_id' => $budgetModels[0]->id, 'amount' => 20000.00, 'expense_date' => '2026-08-10', 'description' => 'Social media marketing',        'reference_number' => 'EXP-002', 'payment_method' => PaymentMethod::UPI,           'status' => 'approved'],
            ['category_id' => $budgetModels[0]->id, 'amount' => 15000.00, 'expense_date' => '2026-09-05', 'description' => 'Print media advertisement',     'reference_number' => 'EXP-003', 'payment_method' => PaymentMethod::CASH,          'status' => 'approved'],
            ['category_id' => $budgetModels[1]->id, 'amount' => 5500.00,  'expense_date' => '2026-09-02', 'description' => 'Stationery and printing paper', 'reference_number' => 'EXP-004', 'payment_method' => PaymentMethod::CASH,          'status' => 'approved'],
            ['category_id' => $budgetModels[1]->id, 'amount' => 8200.00,  'expense_date' => '2026-09-10', 'description' => 'Printer cartridges',            'reference_number' => 'EXP-005', 'payment_method' => PaymentMethod::CREDIT_CARD,  'status' => 'pending'],
            ['category_id' => $budgetModels[2]->id, 'amount' => 45000.00, 'expense_date' => '2026-03-20', 'description' => 'Server upgrade',                'reference_number' => 'EXP-006', 'payment_method' => PaymentMethod::BANK_TRANSFER, 'status' => 'approved'],
            ['category_id' => $budgetModels[2]->id, 'amount' => 32000.00, 'expense_date' => '2026-06-15', 'description' => 'Firewall and security software','reference_number' => 'EXP-007', 'payment_method' => PaymentMethod::BANK_TRANSFER, 'status' => 'approved'],
            ['category_id' => $budgetModels[3]->id, 'amount' => 18000.00, 'expense_date' => '2026-09-12', 'description' => 'Laravel workshop registration', 'reference_number' => 'EXP-008', 'payment_method' => PaymentMethod::UPI,           'status' => 'approved'],
        ];
        foreach ($expenses as $e) {
            Expense::create($e);
        }
    }
}
