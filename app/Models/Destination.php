<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
   protected $fillable = ['name', 'slug', 'country', 'description', 'image', 'is_featured'];

    public function packages(): HasMany
    {
        return $this->hasMany(SafariPackage::class);
    }
}
