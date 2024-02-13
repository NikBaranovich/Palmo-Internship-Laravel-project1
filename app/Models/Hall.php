<?php

namespace App\Models;

use App\Http\Resources\EnabledLayoutItemCollection;
use App\Http\Resources\LayoutItemCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = ['entertainment_venue_id', 'number', 'layout'];

    public function entertainmentVenue()
    {
        return $this->belongsTo(EntertainmentVenue::class);
    }

    public function seatGroups()
    {
        return $this->hasMany(SeatGroup::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function scopeByEntertainmentVenue(Builder $query, $id)
    {
        $query->where($query->qualifyColumn('entertainment_venue_id'), $id);
    }

    public function getItemsList()
    {

        $this->loadMissing('seatGroups.tables', 'seatGroups.seats');

        return $this->seatGroups->map(function ($group) {
            return $group->seats->map(function ($seat) use ($group) {
                return new LayoutItemCollection($seat);
            })->concat($group->tables->map(function ($table) use ($group) {
                return new LayoutItemCollection($table);
            }));
        })->collapse();
    }
    public function getEnabledItemsList()
    {
        $this->loadMissing('seatGroups.tables', 'seatGroups.seats');

        return $this->seatGroups->map(function ($group) {
            return $group->seats->map(function ($seat) use ($group) {
                return new EnabledLayoutItemCollection($seat);
            })->concat($group->tables->map(function ($table) use ($group) {
                return new EnabledLayoutItemCollection($table);
            }));
        })->collapse();
    }

}
