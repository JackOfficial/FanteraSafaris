<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SafariCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Relationship: A category has many safari packages.
     */
    public function safariPackages(): HasMany
    {
        return $this->hasMany(SafariPackage::class, 'safari_category_id');
    }

 public function photo(): MorphOne
{
    // The second argument 'imageable' tells Laravel to look for imageable_id/type
    return $this->morphOne(Photo::class, 'imageable');
}
}