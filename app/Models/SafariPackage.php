<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariPackage extends Model
{
   public function itineraries()
{
    return $this->hasMany(Itinerary::class)->orderBy('day_number');
}
}
