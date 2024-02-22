<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowEntertainmentVenueRequest;
use App\Http\Requests\UpdateVenueRequest;
use App\Models\City;
use App\Models\EntertainmentVenue;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VenueType;
use App\Services\EntertainmentVenueService;

class EntertainmentVenueController extends Controller
{
    public function __construct(
        protected EntertainmentVenueService $service

    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ShowEntertainmentVenueRequest $request)
    {
        $venues = $this->service->index($request);

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
    public function store(UpdateVenueRequest $request, EntertainmentVenue $entertainmentVenue)
    {
        $this->service->save($request, $entertainmentVenue);

        return redirect()
            ->route('admin.entertainment-venues.index')
            ->with('success', 'Entertainment venue successfully created.');
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
        $this->service->save($request, $entertainmentVenue);

        return redirect()
            ->route('admin.entertainment-venues.edit', $entertainmentVenue)
            ->with('success', 'Entertainment venue successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntertainmentVenue $entertainmentVenue)
    {
        $this->service->delete($entertainmentVenue);

        return redirect()
            ->route('admin.entertainment-venues.index')
            ->with('success', 'Entertainment venue successfully deleted.');
    }
}
