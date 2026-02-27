<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function safariPackage()
{
    return $this->belongsTo(SafariPackage::class);
}
}
