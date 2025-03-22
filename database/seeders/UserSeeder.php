<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Developer',
            'email' => 'school@developer.com',
            'role'  => UserRole::ADMIN,
        ])->assignRole(RoleEnum::DEVELOPER);

        User::factory()->create([
            'name'  => 'SuperAdmin',
            'email' => 'school@superadmin.com',
            'role'  => UserRole::ADMIN,
        ])->assignRole(RoleEnum::SUPER_ADMIN);

        User::factory()->create([
            'name'  => 'Admin',
            'email' => 'school@admin.com',
            'role'  => UserRole::ADMIN,
        ])->assignRole(RoleEnum::ADMIN);

        User::factory()->createMany(3)->each(function ($user) {
            $user->assignRole(RoleEnum::USER);
        });
    }
}
