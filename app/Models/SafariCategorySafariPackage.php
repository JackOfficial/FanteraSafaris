<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariCategorySafariPackage extends Pivot
{
    protected $fillable = [
        'safari_category_id',
        'safari_package_id',
    ];

    /**
     * Relationship back to the Safari Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SafariCategory::class, 'safari_category_id');
    }

    /**
     * Relationship back to the Safari Package.
     */
    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class, 'safari_package_id');
    }
}