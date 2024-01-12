<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = ['entertainment_venue_id','layout'];

    public function entertainmentVenue()
    {
        return $this->belongsTo(EntertainmentVenue::class);
    }
}
