<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AcademySectionType: int implements HasLabel
{
    case BOYS  = 0;
    case GIRLS = 1;
    case BOTH  = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::BOYS  => __('Boys'),
            self::GIRLS => __('Girls'),
            self::BOTH  => __('Both'),
        };
    }

    // Find case by value and display as label

    public static function getValue(string $value): ?string
    {
        foreach (self::cases() as $case) {
            if ($value == $case->value) {
                return $case->getLabel();
            }
        }

        return null;
    }

    // Display cases as an array
    
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
