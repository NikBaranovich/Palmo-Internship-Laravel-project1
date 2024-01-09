<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $venueTypes = VenueType::all();

        $venues = EntertainmentVenue::all();

        $venues = $this->venue->query()
            ->when($request->has('sort_by'), function (Builder $query) use ($request) {
                $query->orderBy(
                    $request->input('sort_by'),
                    $request->input('sort_order', 'asc')
                );
            })
            ->paginate(10);

        return view('admin.entertainment-venues', compact('venueTypes', 'venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EntertainmentVenue $venue)
    {
        $venueTypes = VenueType::all();

        return view('admin.entertainment-venues-form', compact('venue', 'venueTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->venue->create($request->except('_token'));

        return redirect()
            ->route('admin.entertainment_venues.index')
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
