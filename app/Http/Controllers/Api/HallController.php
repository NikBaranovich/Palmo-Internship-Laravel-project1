<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnabledHallItemCollection;
use App\Http\Resources\HallItemCollection;
use App\Http\Resources\LayoutItemCollection;
use App\Http\Resources\SeatGroupCollection;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function search(Request $request)
    {
        return Hall::query()
            ->where('entertainment_venue_id',  $request->query('venueId'))
            ->with(['seatGroups.seats', 'seatGroups.tables'])
            ->get(['id', 'number'])
            ->toArray();
    }

    public function getHallById(Request $request)
    {
        return HallItemCollection::collection(
            Hall::query()
                ->with(['seatGroups'])
                ->where('id', $request->query('hall_id'))
                ->get()
        )
            ->first();
    }

    public function getEnabledHallElements(Request $request)
    {



        $hallId = Session::query()
            ->where('id', request()->query('session_id'))
            ->value('hall_id');

        return EnabledHallItemCollection::collection(
            Hall::query()
                ->with(['seatGroups'])
                ->where('id', $hallId)
                ->get()
        )
            ->first();
    }
}
