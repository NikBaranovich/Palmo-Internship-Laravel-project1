<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'start_time', 'end_time', 'hall_id'];

    protected $hidden = ['created_at', 'updated_at'];

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

    public function scopeByStartDate(Builder $query, $startDate)
    {


        $query
            ->when($startDate, function (Builder $query) use ($startDate) {
                $startDate = Carbon::parse($startDate)->startOfDay();

                $endDate = clone $startDate;
                $endDate->endOfDay();

                $dateRange = [
                    $startDate,
                    $endDate
                ];
                $query->whereDate($query->qualifyColumn('start_time'), $dateRange);
            });
    }

    public function scopeByCity(Builder $query, $city)
    {
        $query
            ->when($city, function (Builder $query) use ($city) {
                $query->whereHas('hall.entertainmentVenue', function (Builder $query) use ($city) {
                    $query->where($query->qualifyColumn('city_id'), $city);
                });
            });
    }

    public function scopeByVenues(Builder $query, $venues)
    {
        $query->when($venues, function (Builder $query) use ($venues) {
            $query->whereHas('hall.entertainmentVenue', function (Builder $query) use ($venues) {
                $query->whereIn($query->qualifyColumn('id'), $venues);
            });
        });
    }
}
