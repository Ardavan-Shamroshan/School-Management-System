<?php

namespace App\Models;

use App\Enums\SmsProviderEnum;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'social_network' => 'array',
            'email_settings' => 'array',
            'sms_provider'   => SmsProviderEnum::class
        ];
    }
}