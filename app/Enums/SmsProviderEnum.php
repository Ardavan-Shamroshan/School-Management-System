<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SmsProviderEnum: string implements HasLabel
{
    case MELIPAYAMAK         = 'Melipayamak';
    case MELIPAYAMAK_PATTERN = 'Melipayamak pattern';
    case KAVENEGAR           = 'Kavenegar';
    case SMSIR               = 'Sms ir';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MELIPAYAMAK         => __('Melipayamak'),
            self::MELIPAYAMAK_PATTERN => __('Melipayamak pattern'),
            self::KAVENEGAR           => __('Kavenegar'),
            self::SMSIR               => __('Sms ir'),
        };
    }
}