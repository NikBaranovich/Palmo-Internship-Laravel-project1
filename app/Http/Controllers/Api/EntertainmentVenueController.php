<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EntertainmentVenue;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VenueType;

class EntertainmentVenueController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function search(Request $request)
    {
        // $token = $request->header('X-CSRF-TOKEN');
        // if (!$token || $token !== csrf_token()) {
        //     abort(401);
        // }

        return EntertainmentVenue::query()
            ->byName($request->query('name'))
            ->get(['id', 'name'])
            ->toArray();
    }
}
