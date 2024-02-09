<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntertainmentVenueCollection;
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
        return EntertainmentVenueCollection::collection(
            $this->entertainmentVenue
                ->query()
                ->byCity($request->query('city'))
                ->byEvent($request->query('event'))
                ->get()
        );
    }

    public function getList(Request $request)
    {
        return $this->entertainmentVenue
            ->query()
            ->byCity($request->query('city'))
            ->byEvent($request->query('event'))
            ->get(['id', 'name'])
            ->toArray();
    }

    public function getByCity(Request $request)
    {
        return $this->entertainmentVenue->query()
            ->when($request->query('city'), function (Builder $query) use ($request) {
                $query->where($query->qualifyColumn('city_id'), $request->query('city'));
            })->get(['id', 'name']);
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
