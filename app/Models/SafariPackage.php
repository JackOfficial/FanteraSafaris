<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SafariPackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'slug', 
        'summary', 
        'description', 
        'price', 
        'duration_days', 
        'destination_id', // Swapped 'location' for 'destination_id'
        'difficulty', 
        'safari_category_id', 
        'is_featured', 
        'status', 
        'meta_title', 
        'meta_description'
    ];

    /**
     * Define attribute casting for Laravel 12.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'duration_days' => 'integer',
            'destination_id' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($safari) {
            // Automatically update slug if name changes or slug is missing
            if (empty($safari->slug) || $safari->isDirty('name')) {
                $safari->slug = Str::slug($safari->name);
            }
        });
    }

    /**
     * Relationship to the Destination (Park/City)
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Relationship to Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SafariCategory::class, 'safari_category_id');
    }

    /**
     * Relationship to ALL photos (Gallery + Featured)
     */
    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    /**
     * Relationship to the single Featured Photo (Cover)
     */
    public function photo(): MorphOne
    {
        return $this->morphOne(Photo::class, 'imageable')->where('type', 'featured');
    }

    /**
     * Relationship to Itinerary Days
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }
    
    /**
     * Accessor for formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 0);
    }
}