<?php

namespace App\Enums;

use App\Support\Traits\EnumBuilder;

enum KavenegarEnum: string {
	use EnumBuilder;

	case     RECEIPT  = 'Receipt';
	case     CLOSED   = 'Closed';
	case     NOT_HELD = 'NotHeld';
	case     ABSENCE  = 'Absence';
	case     REPORT   = 'Report';

	public function pattern(): string {
		return match ($this) {
			self::RECEIPT  => config('kavenegar.patterns.receipt'),
			self::CLOSED   => config('kavenegar.patterns.closed'),
			self::NOT_HELD => config('kavenegar.patterns.notHeld'),
			self::ABSENCE  => config('kavenegar.patterns.absence'),
			self::REPORT   => config('kavenegar.patterns.report'),
		};
	}

	public function label(): string {
		return match ($this) {
			self::RECEIPT  => __('Receipt'),
			self::CLOSED   => __('Academy is closed'),
			self::NOT_HELD => __('Class not held'),
			self::ABSENCE  => __('Student absence'),
			self::REPORT   => __('Report'),
		};
	}
}
