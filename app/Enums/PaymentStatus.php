<?php

namespace App\Enums;

use App\Traits\HasEnumOptions;

enum PaymentStatus: int
{
    use HasEnumOptions;

    case PENDING = 0;
    case COMPLETED = 1;
    case FAILED = 2;
    case REFUNDED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
            self::REFUNDED => 'blue',
        };
    }
}
