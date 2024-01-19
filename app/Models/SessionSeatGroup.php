<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSeatGroup extends Model
{
    use HasFactory;

    public $table = "session_seat_groups";

    protected $fillable = ['seat_group_id', 'session_id', 'price'];

    public function sessions()
    {
        return $this->belongsTo(Session::class);
    }
    public function seatGroup()
    {
        return $this->belongsTo(SeatGroup::class);
    }

    public function getSessionSeatPriceAttribute()
    {
        $sessionSeatGroupQuery = $this->query()
            ->with('seatGroup.seats')
            ->whereHas('seatGroup.seats', function (Builder $query) {
                $query->where(
                    $query->qualifyColumn('id'),
                    request('seat_id')
                );
            });

        return $sessionSeatGroupQuery
            ->where(
                $sessionSeatGroupQuery->qualifyColumn('session_id'),
                request('session_id')
            )
            ->value('price');
    }
}
