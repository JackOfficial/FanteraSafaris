<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}