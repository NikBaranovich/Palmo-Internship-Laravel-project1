<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EntertainmentVenue;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VenueType;

class EntertainmentVenueController extends Controller
{
    public function search(Request $request)
    {
        return EntertainmentVenue::query()
            ->where('name', 'LIKE', "%{$request->query('name')}%")
            ->get(['id', 'name'])
            ->toArray();
    }
}
