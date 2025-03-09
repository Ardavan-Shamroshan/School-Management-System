<?php

namespace App\Enums;

enum OTPType: int
{
    case LOGIN = 0;
    case RECOVERY = 1;
}
