<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EntertainmentVenue;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VenueType;

class EntertainmentVenueController extends Controller
{
    public function __construct(protected EntertainmentVenue $entertainmentVenue)
    {
        $this->middleware('web');
    }

    public function index(Request $request)
    {
        return $this->entertainmentVenue->query()->get(['id', 'name']);
    }

    public function search(Request $request)
    {
        // $token = $request->header('X-CSRF-TOKEN');
        // if (!$token || $token !== csrf_token()) {
        //     abort(401);
        // }
        return $this->entertainmentVenue->query()
            ->byName($request->query('name'))
            ->get(['id', 'name'])
            ->toArray();
    }
}
