<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns\HasCustomLayout;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    use HasCustomLayout;
}
