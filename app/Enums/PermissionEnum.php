<?php

namespace App\Enums;

enum PermissionEnum: string {
	case DEVELOP                 = "develop";
	case FULL_CONTROL            = "full-control";
	case MANAGE_ACADEMY_SECTIONS = "manage-academy-sections";
	case MANAGE_FINANCES         = "manage-finances";
	case MANAGE_COURSES          = "manage-courses";
	case MANAGE_REPORTS          = "manage-reports";
	case MANAGE_USERS            = "manage-users";
	case MANAGE_STUDENTS         = "manage-students";
	case MANAGE_TEACHERS         = "manage-teachers";
	case MANAGE_MESSAGES         = "manage-messages";
	case MANAGE_ROLES            = "manage-roles";
	case MANAGE_BACKUPS          = "manage-backups";
	case MANAGE_FILES            = "manage-files";
	case MANAGE_APP              = "manage-app";
	case MANAGE_LOGS             = "manage-logs";

	public function label(): string {
		return match ($this) {
			self::MANAGE_APP              => __('Manage App'),
			self::MANAGE_LOGS             => __('Manage Logs'),
			self::MANAGE_FILES            => __('Manage Files'),
			self::MANAGE_ROLES            => __('Manage Roles'),
			self::FULL_CONTROL            => __('Full Control'),
			self::MANAGE_ACADEMY_SECTIONS => __('Manage CourseActions Section'),
			self::MANAGE_COURSES          => __('Manage Courses'),
			self::MANAGE_BACKUPS          => __('Manage Backups'),
			self::MANAGE_FINANCES         => __('Manage Finances'),
			self::MANAGE_MESSAGES         => __('Manage Messages'),
			self::MANAGE_REPORTS          => __('Manage Reports'),
			self::MANAGE_STUDENTS         => __('Manage Students'),
			self::MANAGE_TEACHERS         => __('Manage Teachers'),
			self::MANAGE_USERS            => __('Manage Users'),
			self::DEVELOP                 => __('Develop'),
		};
	}

	public static function toArray(): array {
		return array_column(self::cases(), 'value');
	}
}
