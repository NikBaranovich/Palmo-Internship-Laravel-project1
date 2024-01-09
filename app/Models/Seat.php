<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['number', 'seat_group_id'];

    public function seatGroup()
    {
        return $this->belongsTo(SeatGroup::class);
    }
}
