<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use Illuminate\Database\Seeder;

class SalesInvoiceSeeder extends Seeder
{
    public function run(): void
    {
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

        // Payment for Invoice 1 (fully paid)
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
    }
}
