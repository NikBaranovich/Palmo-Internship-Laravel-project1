<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function search(Request $request)
    {
        // dd($request->query('venueId'));
        return dd(Hall::query()
            ->where('entertainment_venue_id',  $request->query('venueId'))
            ->with(['seatGroups', 'seatGroups.seats', 'seatGroups.tables'])
            ->get(['id', 'layout'])
            ->toArray());
    }
}
