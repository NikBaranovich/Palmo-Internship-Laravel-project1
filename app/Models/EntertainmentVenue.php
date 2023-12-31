<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainmentVenue extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    protected $casts = [];

    public function venueType()
    {
        return $this->belongsTo(VenueType::class);
    }

    public function halls()
    {
        return $this->belongsToMany(Hall::class);
    }
}
