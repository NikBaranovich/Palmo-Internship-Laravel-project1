<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'overview',
        'trailer_url',
        'poster_path',
        'backdrop_path',
        'release_date'
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Session::class);
    }

    public function genres()
    {
        return $this->belongsToMany(EventGenre::class, 'event_event_genre');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function scopeTopEventsByTickets($query, $limit = 5)
    {
        $query
            ->withCount('tickets')
            ->orderByDesc('tickets_count')
            ->limit($limit);
    }
    public function scopeHasSessions($query)
    {
        $query
            ->has('sessions');
    }
}
