<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Photo extends Model
{
    protected $fillable = ['path', 'type', 'sort_order'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}