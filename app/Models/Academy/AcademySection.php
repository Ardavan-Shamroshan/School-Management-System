<?php

namespace App\Models\Academy;

use App\Enums\AcademySectionType;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademySection extends Model
{
    use SoftDeletes, Sluggable;

    protected $fillable = ['name', 'image', 'description', 'slug', 'type'];
    protected $table    = 'academy_sections';

    protected function casts(): array
    {
        return [
            'type' => AcademySectionType::class
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'         => 'name',
                'includeTrashed' => true
            ]
        ];
    }

    public function gender(): Attribute
    {
        return Attribute::make(
            get: function () {
                return AcademySectionType::getValue($this->type->value);
            }
        );
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
