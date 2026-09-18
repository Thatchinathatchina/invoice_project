<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id',
        'amount',
        'expense_date',
        'description',
        'reference_number',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'payment_method' => PaymentMethod::class,
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'category_id');
    }
}
