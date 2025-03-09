<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }
}
