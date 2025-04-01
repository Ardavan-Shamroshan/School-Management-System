<?php

namespace App\Enums;

enum EmailProviderEnum: string
{
    case SMTP     = 'SMTP';
    case MAILGUN  = 'Mailgun';
    case SES      = 'Amazon SES';
    case POSTMARK = 'Postmark';

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [$item->value => __($item->name)])
            ->toArray();
    }
}