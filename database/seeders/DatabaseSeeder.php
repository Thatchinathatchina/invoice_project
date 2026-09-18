<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ProductType;
use App\Models\Budget;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin User ──────────────────────────────────────
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // ── Customers ───────────────────────────────────────
        $customers = [
            ['name' => 'Rajesh Kumar',    'email' => 'rajesh@example.com',    'phone' => '9876543001', 'address' => '12 MG Road',       'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'tax_number' => '33AABCU9603R1ZM'],
            ['name' => 'Priya Sharma',    'email' => 'priya@example.com',     'phone' => '9876543002', 'address' => '45 Brigade Road',   'city' => 'Bangalore', 'state' => 'Karnataka',   'country' => 'India', 'tax_number' => '29AABCU9603R1ZP'],
            ['name' => 'Anand Mehta',     'email' => 'anand@example.com',     'phone' => '9876543003', 'address' => '78 Bandra West',    'city' => 'Mumbai',    'state' => 'Maharashtra', 'country' => 'India', 'tax_number' => '27AABCU9603R1ZQ'],
            ['name' => 'Lakshmi Iyer',    'email' => 'lakshmi@example.com',   'phone' => '9876543004', 'address' => '23 Anna Nagar',     'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'tax_number' => '33AABCU9603R1ZR'],
            ['name' => 'Vikram Singh',    'email' => 'vikram@example.com',    'phone' => '9876543005', 'address' => '56 Connaught Place', 'city' => 'New Delhi', 'state' => 'Delhi',       'country' => 'India', 'tax_number' => '07AABCU9603R1ZS'],
        ];
        foreach ($customers as $c) {
            Customer::create($c);
        }

        // ── Suppliers ───────────────────────────────────────
        $suppliers = [
            ['name' => 'Tech Solutions Pvt Ltd',  'email' => 'info@techsolutions.com',  'phone' => '9800001001', 'address' => '10 IT Park',       'city' => 'Pune',      'state' => 'Maharashtra', 'country' => 'India', 'tax_number' => '27AAACT5645R1Z5'],
            ['name' => 'Global Supplies Co',      'email' => 'sales@globalsupplies.com','phone' => '9800001002', 'address' => '22 Industrial Area','city' => 'Hyderabad', 'state' => 'Telangana',   'country' => 'India', 'tax_number' => '36AAACG1234R1Z6'],
            ['name' => 'Star Office Equipments',  'email' => 'star@officeequip.com',    'phone' => '9800001003', 'address' => '5 Nehru Street',   'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'tax_number' => '33AAACS7890R1Z7'],
        ];
        foreach ($suppliers as $s) {
            Supplier::create($s);
        }

        // ── Products / Services ─────────────────────────────
        $products = [
            ['name' => 'Web Development Service',   'description' => 'Full-stack web application development',   'price' => 50000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'Mobile App Development',     'description' => 'Native/Cross-platform mobile app',        'price' => 75000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'UI/UX Design',               'description' => 'User interface and experience design',    'price' => 25000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'Cloud Hosting (Annual)',      'description' => 'Annual cloud server hosting package',     'price' => 12000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'Laptop - Dell Inspiron',      'description' => 'Dell Inspiron 15, i5, 8GB RAM, 512GB SSD','price' => 55000.00, 'type' => ProductType::PRODUCT->value],
            ['name' => 'Office Chair - Ergonomic',    'description' => 'Premium ergonomic office chair',          'price' => 8500.00,  'type' => ProductType::PRODUCT->value],
            ['name' => 'Printer - HP LaserJet',       'description' => 'HP LaserJet Pro MFP M428fdw',             'price' => 32000.00, 'type' => ProductType::PRODUCT->value],
            ['name' => 'Software License - Annual',   'description' => 'Annual software license subscription',    'price' => 15000.00, 'type' => ProductType::PRODUCT->value],
        ];
        foreach ($products as $p) {
            Product::create($p);
        }

        // ── Budgets ─────────────────────────────────────────
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

        // ── Expenses (linked to budgets) ────────────────────
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

        // ── Sales Invoices ──────────────────────────────────
        // Invoice 1: Paid
        $si1 = SalesInvoice::create([
            'customer_id'    => 1,
            'invoice_number' => 'INV-00001',
            'invoice_date'   => '2026-09-01',
            'due_date'       => '2026-09-15',
            'subtotal'       => 0,
            'discount'       => 5000,
            'tax'            => 0,
            'total'          => 0,
            'paid_amount'    => 0,
            'status'         => InvoiceStatus::PAID->value,
            'notes'          => 'Website redesign project - Phase 1',
        ]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si1->id, 'product_id' => 1, 'description' => 'Website redesign',        'quantity' => 1, 'unit_price' => 50000, 'tax_amount' => 9000,  'discount' => 0, 'total' => 50000]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si1->id, 'product_id' => 3, 'description' => 'UI/UX for website',       'quantity' => 1, 'unit_price' => 25000, 'tax_amount' => 4500,  'discount' => 0, 'total' => 25000]);
        $si1->calculateTotals();
        $si1->save();

        // Invoice 2: Pending
        $si2 = SalesInvoice::create([
            'customer_id'    => 2,
            'invoice_number' => 'INV-00002',
            'invoice_date'   => '2026-09-10',
            'due_date'       => '2026-09-25',
            'subtotal'       => 0,
            'discount'       => 0,
            'tax'            => 0,
            'total'          => 0,
            'paid_amount'    => 0,
            'status'         => InvoiceStatus::PENDING->value,
            'notes'          => 'Mobile app development project',
        ]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si2->id, 'product_id' => 2, 'description' => 'Flutter app development', 'quantity' => 1, 'unit_price' => 75000, 'tax_amount' => 13500, 'discount' => 0, 'total' => 75000]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si2->id, 'product_id' => 4, 'description' => 'Hosting for 1 year',      'quantity' => 1, 'unit_price' => 12000, 'tax_amount' => 2160,  'discount' => 0, 'total' => 12000]);
        $si2->calculateTotals();
        $si2->save();

        // Invoice 3: Overdue
        $si3 = SalesInvoice::create([
            'customer_id'    => 3,
            'invoice_number' => 'INV-00003',
            'invoice_date'   => '2026-08-01',
            'due_date'       => '2026-08-15',
            'subtotal'       => 0,
            'discount'       => 2000,
            'tax'            => 0,
            'total'          => 0,
            'paid_amount'    => 0,
            'status'         => InvoiceStatus::OVERDUE->value,
            'notes'          => 'IT equipment order - overdue',
        ]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si3->id, 'product_id' => 5, 'description' => 'Laptop for client office', 'quantity' => 2, 'unit_price' => 55000, 'tax_amount' => 19800, 'discount' => 0, 'total' => 110000]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si3->id, 'product_id' => 6, 'description' => 'Ergonomic chairs',         'quantity' => 4, 'unit_price' => 8500,  'tax_amount' => 6120,  'discount' => 0, 'total' => 34000]);
        $si3->calculateTotals();
        $si3->save();

        // Invoice 4: Draft
        $si4 = SalesInvoice::create([
            'customer_id'    => 4,
            'invoice_number' => 'INV-00004',
            'invoice_date'   => '2026-09-18',
            'due_date'       => '2026-10-02',
            'subtotal'       => 0,
            'discount'       => 0,
            'tax'            => 0,
            'total'          => 0,
            'paid_amount'    => 0,
            'status'         => InvoiceStatus::DRAFT->value,
            'notes'          => 'Software license renewal - Draft',
        ]);
        SalesInvoiceItem::create(['sales_invoice_id' => $si4->id, 'product_id' => 8, 'description' => 'Annual license x3 users', 'quantity' => 3, 'unit_price' => 15000, 'tax_amount' => 8100, 'discount' => 0, 'total' => 45000]);
        $si4->calculateTotals();
        $si4->save();

        // ── Payment for Invoice 1 (fully paid) ──────────────
        Payment::create([
            'payable_type'     => SalesInvoice::class,
            'payable_id'       => $si1->id,
            'amount'           => $si1->total,
            'payment_date'     => '2026-09-12',
            'payment_method'   => PaymentMethod::BANK_TRANSFER,
            'reference_number' => 'PAY-TXN-001',
            'notes'            => 'Full payment received via NEFT',
            'status'           => PaymentStatus::COMPLETED,
        ]);
        $si1->calculateTotals();
        $si1->save();

        // ── Purchase Invoices ───────────────────────────────
        $pi1 = PurchaseInvoice::create([
            'supplier_id'    => 1,
            'invoice_number' => 'PINV-00001',
            'invoice_date'   => '2026-08-20',
            'due_date'       => '2026-09-20',
            'subtotal'       => 0,
            'tax_amount'     => 0,
            'discount'       => 0,
            'total_amount'   => 0,
            'status'         => InvoiceStatus::PAID->value,
            'notes'          => 'Server hardware purchase',
        ]);
        PurchaseInvoiceItem::create(['purchase_invoice_id' => $pi1->id, 'product_id' => 5, 'description' => 'Dell servers for data center', 'quantity' => 3, 'unit_price' => 55000, 'tax_amount' => 29700, 'discount' => 5000, 'total' => 160000]);
        $pi1->calculateTotals();
        $pi1->save();

        Payment::create([
            'payable_type'     => PurchaseInvoice::class,
            'payable_id'       => $pi1->id,
            'amount'           => $pi1->total_amount,
            'payment_date'     => '2026-09-01',
            'payment_method'   => PaymentMethod::BANK_TRANSFER,
            'reference_number' => 'PAY-TXN-002',
            'notes'            => 'Supplier payment for servers',
            'status'           => PaymentStatus::COMPLETED,
        ]);

        $pi2 = PurchaseInvoice::create([
            'supplier_id'    => 2,
            'invoice_number' => 'PINV-00002',
            'invoice_date'   => '2026-09-05',
            'due_date'       => '2026-10-05',
            'subtotal'       => 0,
            'tax_amount'     => 0,
            'discount'       => 0,
            'total_amount'   => 0,
            'status'         => InvoiceStatus::PENDING->value,
            'notes'          => 'Office equipment purchase',
        ]);
        PurchaseInvoiceItem::create(['purchase_invoice_id' => $pi2->id, 'product_id' => 7, 'description' => 'HP Printer for office',       'quantity' => 2, 'unit_price' => 32000, 'tax_amount' => 11520, 'discount' => 0, 'total' => 64000]);
        PurchaseInvoiceItem::create(['purchase_invoice_id' => $pi2->id, 'product_id' => 6, 'description' => 'Ergonomic chairs for office', 'quantity' => 6, 'unit_price' => 8500,  'tax_amount' => 9180,  'discount' => 0, 'total' => 51000]);
        $pi2->calculateTotals();
        $pi2->save();

        $this->command->info('✅ Database seeded successfully with sample data!');
    }
}
