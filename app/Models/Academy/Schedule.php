<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Stringable;

class Schedule extends Model
{
    protected $fillable = ['start_time', 'end_time', 'sort'];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function time(): Attribute
    {
        return new Attribute(
            get: fn() => str($this->start_time)
                ->when(
                    $this->end_time,
                    fn(Stringable $string) => $string->append(' - ')->append($this->end_time)
                )
                ->value()
        );
    }
}
