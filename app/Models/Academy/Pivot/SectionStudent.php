<?php

namespace App\Models\Academy\Pivot;

use App\Models\Academy\Section;
use App\Models\Academy\Student;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SectionStudent extends Pivot
{
    protected $table = 'section_student';

    protected function casts(): array
    {
        return [
            'invoices' => 'array'
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
