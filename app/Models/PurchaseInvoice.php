<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount',
        'total_amount',
        'status',
        'currency_code',
        'exchange_rate',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status' => InvoiceStatus::class,
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseInvoiceItems()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
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
        $items = $this->purchaseInvoiceItems;
        $this->subtotal = $items->sum('total');
        $this->tax_amount = $items->sum('tax_amount');
        $this->total_amount = ($this->subtotal + $this->tax_amount) - $this->discount;
    }

    /**
     * Get the paid amount from payments.
     */
    public function getPaidAmountAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount');
        }
        return (float) $this->payments()->sum('amount');
    }

    /**
     * Get the balance due.
     */
    public function getBalanceDueAttribute(): float
    {
        return (float) $this->total_amount - $this->paid_amount;
    }

    /**
     * Generate a unique purchase invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $latest = static::latest('id')->first();
        $number = $latest ? ((int) substr($latest->invoice_number, 5)) + 1 : 1;
        return 'PINV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the total in base currency (INR)
     */
    public function getBaseTotalAttribute()
    {
        return $this->total_amount;
    }

    /**
     * Get the foreign currency total.
     */
    public function getForeignTotalAttribute()
    {
        if ($this->currency_code === 'INR' || !$this->exchange_rate) {
            return $this->total_amount;
        }
        return $this->total_amount / $this->exchange_rate;
    }
}
