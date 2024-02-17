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
        return $this->belongsToMany(Genre::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function scopeTopEventsByTickets($query, $ticket_top)
    {
        $query->when($ticket_top, function (Builder $query) {
            $query->withCount('tickets')
                ->orderByDesc('tickets_count');
        });
    }

    public function scopeTopEventsByViews($query, $views_top)
    {
        $query->when($views_top, function (Builder $query) {
            $query
                ->orderByDesc('views_count');
        });
    }
    public function scopeTopEventsByRate($query, $rate_top)
    {
        $query->when($rate_top, function (Builder $query) {
            $query->withAvg('ratings', 'vote')
                ->orderByDesc('ratings_avg_vote', 'desc');
        });
    }
    public function scopeTopEventsByUser($query, $user)
    {
        $genres = Event::when($user, function (Builder $query) use ($user) {
            $query->with('genres')
                ->whereHas('tickets.user', function (Builder $query) use ($user) {
                    $query->where('id', $user);
                });
        })->get()->pluck('genres.*.id')->flatten()->toArray();

        $genres = collect($genres)->groupBy(function ($genreId) {
            return $genreId;
        })->map(function ($genreIds) {
            return count($genreIds);
        })->sortDesc()->keys()->take(5)->toArray();
        if(!count($genres)){
            return;
        }
        $query->when($genres, function (Builder $query) use ($genres, $user) {
            $query->whereDoesntHave('tickets', function (Builder $query) use ($user) {
                $query->where('user_id', $user);
            })->byGenres($genres);
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
