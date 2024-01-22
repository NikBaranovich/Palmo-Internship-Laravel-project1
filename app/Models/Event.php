<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trailer_url',
        'poster_picture_url',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Session::class);
    }

    public function scopeTopEventsByTickets($query, $limit = 5)
    {
        return $query
            ->withCount('tickets')
            ->orderByDesc('tickets_count')
            ->limit($limit);
    }
}
