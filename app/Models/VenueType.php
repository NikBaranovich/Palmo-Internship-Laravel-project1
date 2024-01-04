<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueType extends Model
{
    use HasFactory;

    public function entertainmentVenues()
    {
        return $this->hasMany(EntertainmentVenue::class);
    }
}