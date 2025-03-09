<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'verified_at'       => 'timestamp',
        ];
    }

    // Get user role badge from UserRole enum class
    public function roleBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => UserRole::toArray()[$this->role->value]
        );
    }

    // Get user all roles badges from RoleEnum class
    public function rolesBadges(): Attribute
    {
        return Attribute::make(
            get: function () {
                $rolesArray = $this->roles()->pluck('name')->toArray();

                return Arr::join(
                    Arr::map($rolesArray, fn($role) => RoleEnum::getValue($role)),
                    ', '
                );
            }
        );
    }

    // Get avatar badge from the first letter of name
    public function avatarBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => strtoupper(substr($this->name, 0, 1))
        );
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN;
    }
}
