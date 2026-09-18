<?php

namespace App\Enums;

enum ProductType: int
{
    use \App\Traits\HasEnumOptions;

    case PRODUCT = 1;
    case SERVICE = 2;

    public function label(): string
    {
        return match ($this) {
            self::PRODUCT => 'Product',
            self::SERVICE => 'Service',
        };
    }
}
