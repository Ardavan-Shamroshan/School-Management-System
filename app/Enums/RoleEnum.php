<?php

namespace App\Enums;

enum RoleEnum: string {
	case DEVELOPER   = 'developer';
	case SUPER_ADMIN = 'super-admin';
	case ADMIN       = 'admin';
	case USER        = 'user';
	case STUDENT     = 'student';
	case TEACHER     = 'teacher';

	public function getLabel(): string {
		return match ($this) {
			self::DEVELOPER   => __('Developer'),
			self::SUPER_ADMIN => __('Super Admin'),
			self::ADMIN       => __('Admin'),
			self::USER        => __('User'),
			self::STUDENT     => __('Student'),
			self::TEACHER     => __('Teacher'),
		};
	}

	public function getBadge(): string {
		return match ($this) {
			self::DEVELOPER              => 'danger',
			self::SUPER_ADMIN            => 'info',
			self::ADMIN                  => 'warning',
			self::USER                   => 'success',
			self::STUDENT, self::TEACHER => 'primary',
		};
	}

	// Find case by value and display as label
	public static function getValue(string $value): ?string {
		foreach(self::cases() as $case) {
			if($value == $case->value) {
				return $case->getLabel();
			}
		}

		return null;
	}


	// Display cases as an array
	public static function toArray(): array {
		return array_column(self::cases(), 'value');
	}
}
