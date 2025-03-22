<?php

namespace App\Models\Academy;

use App\Models\Academy\Pivot\SectionStudent;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use function App\Support\IRRCurrency;

class Section extends Model {

	use SoftDeletes, Sluggable;

	protected $fillable = [
		'course_id',
		'teacher_id',
		'name',
		'slug',
		'schedules',
		'start_date',
		'end_date',
		'start_time',
		'price',
		'status',
		'updated_by',
		'created_by',
	];

	protected function casts(): array {
		return [
			'schedules' => 'array',
		];
	}

	public function sluggable(): array {
		return [
			'slug' => [
				'source'         => ['course.name', 'id'],
				'includeTrashed' => true
			],
		];
	}

	public function priceCurrency(): Attribute {
		return Attribute::make(
			// get: fn() => IRRCurrency($this->price)
		);
	}

	public function startDateJalali(): Attribute {
		return Attribute::make(
			// get: fn() => $this->start_date ? verta($this->start_date)->format('Y/m/d H:i') : null
		);
	}

	public function schedulesLabel(): Attribute {
		return Attribute::make(
			get: fn() => Arr::join(
				Arr::map($this->schedules, function(string $value) {
					return __(ucfirst($value));
				}), ', '
			),
		);
	}

	public function schedulesBadge(): Attribute {
		return Attribute::make(
			get: fn() => Arr::map($this->schedules ?? [], function(string $value) {
				return mb_substr(__(ucfirst($value)), 0, 1);
			}),
		);
	}

	public function course(): BelongsTo {
		return $this->belongsTo(Course::class);
	}

	public function teacher(): BelongsTo {
		return $this->belongsTo(Teacher::class);
	}

	public function students(): BelongsToMany {
		return $this->belongsToMany(Student::class, table: 'section_student')
			->withPivot(['invoices', 'paid', 'note', 'draft'])
			->using(SectionStudent::class);
	}
}
