<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = ['day_number', 'title', 'activities', 'accommodation', 'meals'];

public function package()
{
    return $this->belongsTo(SafariPackage::class);
}
}
