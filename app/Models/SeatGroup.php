<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id','name', 'number', 'color', 'hall_id'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
    public function tables()
    {
        return $this->hasMany(Table::class);
    }
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
