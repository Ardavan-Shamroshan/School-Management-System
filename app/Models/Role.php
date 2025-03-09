<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected function casts(): array
    {
        return [
            'name' => RoleEnum::class,
        ];
    }
}
