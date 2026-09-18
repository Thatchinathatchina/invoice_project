<?php

namespace App\Enums;

/**
 * StatusEnum represents the core active/inactive states across models.
 * Replaces hardcoded string checks in validation and business logic.
 */
enum StatusEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    /**
     * Get an array of all scalar values for validation rules.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
