<?php

namespace App\Enums;

use App\Traits\BaseEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AcademySectionType: int implements HasLabel, HasIcon, HasColor
{
    use BaseEnum;

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

    public function getIcon(): string
    {
        return match ($this) {
            self::BOYS  => 'heroicon-o-academic-cap',
            self::GIRLS => 'heroicon-o-academic-cap',
            self::BOTH  => 'heroicon-o-academic-cap',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::BOYS  => 'info',
            self::GIRLS => 'fuchsia',
            self::BOTH  => 'primary',
        };
    }
}
