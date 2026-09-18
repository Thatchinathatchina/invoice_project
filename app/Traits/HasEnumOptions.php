<?php

namespace App\Traits;

trait HasEnumOptions
{
    /**
     * Get an array of enum values and labels for dropdowns or options.
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($enum) {
            return [
                $enum->value => method_exists($enum, 'label') ? $enum->label() : $enum->name,
            ];
        })->toArray();
    }

    /**
     * Get an array of all enum values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
