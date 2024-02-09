<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeTopEventsByTickets($query, $ticket_top, $limit = 5)
    {
        $query->when($ticket_top, function (Builder $query) use ($limit) {
            $query->withCount('tickets')
                ->orderByDesc('tickets_count')
                ->limit($limit);
        });
    }
    public function scopeHasSessions($query)
    {
        $query
            ->has('sessions');
    }

    public function scopeWithLimit($query, $limit)
    {
        $query->when($limit, function (Builder $query) use ($limit) {
            $query->limit($limit);
        });
    }

    public function scopeByGenres($query, $genres)
    {
        $query
            ->when($genres, function (Builder $query) use ($genres) {
                $query->whereHas('genres', function (Builder $query) use ($genres) {
                    $query->whereIn($query->qualifyColumn('id'), $genres);
                });
            });
    }
    public function scopeByTitle($query, $title)
    {
        $query->when($title, function (Builder $query) use ($title) {
            $query->where('title', 'LIKE', "%{$title}%");
        });
    }
}
