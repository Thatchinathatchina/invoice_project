<?php

namespace App\Models;

use App\Enums\BudgetPeriod;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'amount',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => \App\Enums\StatusEnum::class,
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    /**
     * Get total spent from linked expenses.
     */
    public function getTotalSpentAttribute(): float
    {
        if (array_key_exists('expenses_sum_amount', $this->attributes)) {
            return (float) $this->attributes['expenses_sum_amount'];
        }
        return (float) $this->expenses()->sum('amount');
    }

    /**
     * Get remaining budget amount.
     */
    public function getRemainingAttribute(): float
    {
        return (float) $this->amount - $this->total_spent;
    }

    /**
     * Get usage percentage.
     */
    public function getUsagePercentAttribute(): float
    {
        if ((float) $this->amount === 0.0) {
            return 0;
        }
        return round(($this->total_spent / (float) $this->amount) * 100, 2);
    }
}
