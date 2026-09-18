<?php

namespace App\Enums;

use App\Traits\HasEnumOptions;

enum InvoiceStatus: int
{
    use HasEnumOptions;

    case DRAFT = 0;
    case PENDING = 1;
    case PAID = 2;
    case OVERDUE = 3;
    case CANCELLED = 4;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::OVERDUE => 'Overdue',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING => 'yellow',
            self::PAID => 'green',
            self::OVERDUE => 'red',
            self::CANCELLED => 'gray',
        };
    }
}
