<?php

namespace App\Models\Academy;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes, Sluggable;

    protected $fillable = [
        'academy_section_id',
        'name',
        'slug',
        'description',
        'image',
        'created_by',
        'updated_by',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'         => 'name',
                'includeTrashed' => true
            ],
        ];
    }

    public function academySection(): BelongsTo
    {
        return $this->belongsTo(AcademySection::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
