<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainmentVenue extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    protected $casts = [];

    public function venueType()
    {
        return $this->belongsTo(VenueType::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function halls()
    {
        return $this->hasMany(Hall::class);
    }

    public function scopeByName(Builder $query, $name)
    {
        $query->where('name', 'like', "%{$name}%");
    }

    public function scopeByCity(Builder $query, $city)
    {
        $query->when($city, function (Builder $query) use ($city) {
            $query->where($query->qualifyColumn('city_id'), $city);
        });
    }
    public function scopeByEvent(Builder $query, $event)
    {
        $query->when($event, function (Builder $query) use ($event) {
            $query->whereHas('halls.sessions', function (Builder $query) use ($event) {
                $query->where($query->qualifyColumn('event_id'), $event);
            });
        });
    }
}
