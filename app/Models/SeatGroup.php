<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'number'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
