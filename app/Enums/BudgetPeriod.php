<?php

namespace App\Enums;

use App\Traits\HasEnumOptions;

enum BudgetPeriod: int
{
    use HasEnumOptions;

    case MONTHLY = 1;
    case YEARLY = 2;

    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Monthly',
            self::YEARLY => 'Yearly',
        };
    }
}
