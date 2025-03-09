<?php

namespace App\Enums;

enum UserRole: string
{
    case USER  = 'user';
    case ADMIN = 'admin';

    // Display cases as an array
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
