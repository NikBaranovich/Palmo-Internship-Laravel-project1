<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVenueRequest;
use App\Models\City;
use App\Models\EntertainmentVenue;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VenueType;

class EntertainmentVenueController extends Controller
{
    public function __construct(
        protected EntertainmentVenue $venue
    ) {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['id', 'name', 'type', 'city', 'address'];

        $venues = $this->venue->query()
            ->when(
                $request->has('sort_by') && in_array($request->input('sort_by'), $sortableColumns),
                function (Builder $query) use ($request) {
                    $sortBy = $request->input('sort_by');
                    $sortOrder = $request->input('sort_order', 'asc');

                    switch ($sortBy) {
                        case 'city':
                            $query->withAggregate('city', 'name')
                                ->orderBy('city_name', $sortOrder);
                            break;
                        case 'type':
                            $query->withAggregate('venueType', 'name')
                                ->orderBy('venue_type_name', $sortOrder);
                            break;
                        default:
                            $query->orderBy($sortBy, $sortOrder);
                    }
                }
            )
            ->paginate(10);

        return view('admin.entertainment-venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EntertainmentVenue $entertainmentVenue)
    {
        $venueTypes = VenueType::all();
        $cities = City::all();

        return view('admin.entertainment-venues.edit', compact('entertainmentVenue', 'cities', 'venueTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateVenueRequest $request)
    {
        $this->venue->create($request->except('_token'));

        return redirect()
            ->route('admin.entertainment-venues.index')
            ->with('success', 'Venue successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EntertainmentVenue $entertainmentVenue)
    {
        $venueTypes = VenueType::all();
        $cities = City::all();
        return view('admin.entertainment-venues.edit', compact('entertainmentVenue', 'cities', 'venueTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVenueRequest $request, EntertainmentVenue $entertainmentVenue)
    {
        $entertainmentVenue->fill($request->validated());
        $entertainmentVenue->save();

        return redirect()
            ->route('admin.entertainment-venues.edit', $entertainmentVenue)
            ->with('success', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntertainmentVenue $entertainmentVenue)
    {
        $entertainmentVenue->delete();

        return redirect()
            ->route('admin.entertainment-venues.index')
            ->with('success', 'Venue successfully deleted.');
    }

    public function search(Request $request)
    {
        return EntertainmentVenue::query()
            ->where('name', 'LIKE', "%{$request->query('name')}%")
            ->get(['id', 'name'])
            ->toArray();
    }
}
