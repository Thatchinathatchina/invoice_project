<?php

namespace App\Enums;

use App\Traits\HasEnumOptions;

enum PaymentMethod: int
{
    use HasEnumOptions;

    case CASH = 1;
    case BANK_TRANSFER = 2;
    case CREDIT_CARD = 3;
    case UPI = 4;
    case CHEQUE = 5;

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CREDIT_CARD => 'Credit Card',
            self::UPI => 'UPI',
            self::CHEQUE => 'Cheque',
        };
    }
}
