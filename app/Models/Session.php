<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'start_time', 'end_time', 'hall_id'];


    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function sessionSeatGroups()
    {
        return $this->hasMany(SessionSeatGroup::class);
    }

}
