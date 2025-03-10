<?php

namespace App\Enums;


use Filament\Support\Contracts\HasLabel;

enum GenderEnum: int implements HasLabel
{
    case MALE   = 0;
    case FEMALE = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE   => __('Male'),
            self::FEMALE => __('Female'),
            default      => null
        };
    }
}
