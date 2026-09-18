<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Database\Seeder;

class PurchaseInvoiceSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
