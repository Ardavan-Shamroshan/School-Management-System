<?php

namespace App\Models\Academy;

use App\Enums\ScheduleEnum;
use App\Models\Academy\Pivot\SectionStudent;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes, Sluggable;

    protected $fillable = [
        'course_id',
        'teacher_id',
        'schedule_id',
        'name',
        'slug',
        'schedules',
        'start_date',
        'end_date',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            // 'schedules' => ScheduleEnum::class,
            'schedules' => AsEnumCollection::class . ':' . ScheduleEnum::class,
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'         => ['course.name', 'id'],
                'includeTrashed' => true
            ],
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, table: 'section_student')
            ->withPivot(['invoices', 'paid', 'note', 'draft'])
            ->using(SectionStudent::class);
    }
}
