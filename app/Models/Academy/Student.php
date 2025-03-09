<?php

namespace App\Models\Academy;

use App\Models\Academy\Pivot\SectionStudent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = ['name', 'father_name', 'mobile', 'second_mobile', 'gender', 'address', 'info', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return [
            'sections.pivot.invoices' => 'array',
        ];
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, table: 'section_student')
            ->withPivot(['invoices', 'paid', 'note', 'draft'])
            ->using(SectionStudent::class);
    }

    public function lastSection()
    {
        return $this->sections()->latest()->first();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoiceable');
    }
}
