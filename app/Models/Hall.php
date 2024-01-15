<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = ['entertainment_venue_id', 'number', 'layout'];

    public function entertainmentVenue()
    {
        return $this->belongsTo(EntertainmentVenue::class);
    }

    public function seatGroups()
    {
        return $this->hasMany(SeatGroup::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
