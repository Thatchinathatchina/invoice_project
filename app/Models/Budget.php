<?php

namespace App\Models;

use App\Enums\BudgetPeriod;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
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
