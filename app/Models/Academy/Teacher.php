<?php

namespace App\Models\Academy;

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Teacher extends Model
{
    use SoftDeletes, Notifiable;

    protected $fillable = ['user_id', 'name', 'mobile', 'second_mobile', 'gender', 'father_name', 'info', 'address'];

    protected function casts(): array
    {
        return [
            'gender' => GenderEnum::class
        ];
    }

    // Accessors & Mutators

    public function genderLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => GenderEnum::getValue($this->gender?->value),
        );
    }

    // Relationships

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoiceable');
    }
}
