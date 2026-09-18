<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'status',
        'currency_code',
        'exchange_rate',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'status' => InvoiceStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesInvoiceItems()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Calculate totals from line items.
     */
    public function calculateTotals(): void
    {
        $items = $this->salesInvoiceItems;
        $this->subtotal = $items->sum('total');
        $this->tax = $items->sum('tax_amount');
        $this->total = ($this->subtotal + $this->tax) - $this->discount;
        $this->paid_amount = $this->payments()->sum('amount');
    }

    /**
     * Get the balance due.
     */
    public function getBalanceDueAttribute(): float
    {
        return (float) $this->total - (float) $this->paid_amount;
    }

    /**
     * Generate a unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $latest = static::latest('id')->first();
        $number = $latest ? ((int) substr($latest->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Base Total (INR) is already the total
     */
    public function getBaseTotalAttribute()
    {
        return $this->total;
    }

    // Foreign Currency Total
    public function getForeignTotalAttribute()
    {
        if ($this->currency_code === 'INR' || !$this->exchange_rate) {
            return $this->total;
        }
        return $this->total / $this->exchange_rate;
    }
}
