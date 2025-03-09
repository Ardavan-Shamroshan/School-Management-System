<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run(): void {
		// create permissions
		Arr::map(PermissionEnum::toArray(), fn(string $value) => Permission::create(['name' => $value]));

		// create roles
		Arr::map(RoleEnum::toArray(), fn(string $value) => Role::create(['name' => $value]));

		// developer
		Role::query()->where(['name' => RoleEnum::DEVELOPER->value])->first()->syncPermissions(PermissionEnum::DEVELOP);

		// super admin
		Role::query()->where(['name' => RoleEnum::SUPER_ADMIN->value])->first()->syncPermissions(PermissionEnum::FULL_CONTROL);

		// admin
		Role::query()->where(['name' => RoleEnum::ADMIN->value])->first()
			->syncPermissions(Permission::all())
			->revokePermissionTo([
				PermissionEnum::DEVELOP,
				PermissionEnum::MANAGE_REPORTS,
				PermissionEnum::FULL_CONTROL,
				PermissionEnum::MANAGE_FINANCES,
				PermissionEnum::MANAGE_FILES,
				PermissionEnum::MANAGE_BACKUPS
			]);
	}
}
