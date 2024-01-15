<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\SeatGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SeatGroupController extends Controller
{
    public function search(Request $request)
    {
        return SeatGroup::query()
            ->where('hall_id', $request->query('hallId'))
            ->get(['id', 'name', 'number', 'color'])
            ->toArray();
    }
}
