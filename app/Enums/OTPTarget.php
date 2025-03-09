<?php

namespace App\Enums;

enum OTPTarget: string
{
    case MOBILE = 'mobile';
    case EMAIL  = 'email';
}
