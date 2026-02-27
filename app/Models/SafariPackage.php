<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Ensure this is imported
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SafariPackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'summary', 'description', 'price', 
        'duration_days', 'location', 'difficulty', 'image', 
        'category_id', 'is_featured', 'status', 'meta_title', 'meta_description'
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
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        // Use 'saving' instead of 'creating' so it updates 
        // if the name changes in the future.
        static::saving(function ($safari) {
            if (empty($safari->slug) || $safari->isDirty('name')) {
                $safari->slug = Str::slug($safari->name);
            }
        });
    }

    public function category(): BelongsTo
{
    return $this->belongsTo(SafariCategory::class, 'safari_category_id');
}

public function photos()
{
    return $this->morphMany(Photo::class, 'imageable');
}

// Helper to get just the featured image
public function featuredPhoto()
{
    return $this->morphOne(Photo::class, 'imageable')->where('type', 'featured');
}

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }
    
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 0);
    }
}