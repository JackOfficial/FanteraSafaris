<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DestinationSafariPackage extends Pivot
{
    protected $fillable = [
        'destination_id',
        'safari_package_id',
    ];

    /**
     * Relationship back to the Safari Package.
     */
    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class, 'safari_package_id');
    }

    /**
     * Relationship back to the Destination.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}