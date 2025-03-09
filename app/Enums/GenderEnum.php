<?php

namespace App\Enums;


enum GenderEnum: int
{
    case MALE   = 0;
    case FEMALE = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE   => 'Male',
            self::FEMALE => 'Female',
            default      => null
        };
    }

    // Find case by value and display as label
    public static function getValue(?string $value): ?string
    {
        foreach (self::cases() as $case) {
            if (! is_null($value) && $case->value == $value) {
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
