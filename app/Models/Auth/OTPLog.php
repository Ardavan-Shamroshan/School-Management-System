<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\OTPType;

class OTPLog extends Model
{
    use SoftDeletes;

    protected $table   = 'otp_logs';
    protected $guarded = ['id'];

    protected $casts = [
        'used_at' => 'timestamp',
        'type'    => OTPType::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
