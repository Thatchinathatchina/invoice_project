<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payable_id',
        'payable_type',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'payment_method' => PaymentMethod::class,
        'status' => PaymentStatus::class,
    ];

    /**
     * Get the parent payable model (SalesInvoice or PurchaseInvoice).
     */
    public function payable()
    {
        return $this->morphTo();
    }
}
