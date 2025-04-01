<?php

namespace App\Enums;

enum SocialNetworkEnum: string
{
    case WHATSAPP  = 'whatsapp';
    case FACEBOOK  = 'facebook';
    case INSTAGRAM = 'instagram';
    case TWITTER   = 'x_twitter';
    case YOUTUBE   = 'youtube';
    case LINKEDIN  = 'linkedin';
    // case TIKTOK    = 'tiktok';
    case PINTEREST = 'pinterest';
    case TELEGRAM  = 'telegram';

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [$item->value => __($item->name)])
            ->toArray();
    }
}
