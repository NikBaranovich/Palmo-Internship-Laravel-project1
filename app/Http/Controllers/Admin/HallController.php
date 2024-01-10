<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Table;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function __construct(
        protected Hall $hall,
        protected SeatGroup $seatGroup,
        protected EntertainmentVenue $entertainmentVenue,
    ) {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hall $hall, EntertainmentVenue $entertainmentVenue)
    {
        return view('admin.hall.edit', compact('hall', 'entertainmentVenue'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $seatGroups = $request->input('groups', []);
        $updatedLayout = [];
        foreach ($seatGroups as $seatGroupData) {
            $seatGroupData = json_decode($seatGroupData, true);
            $seatGroup = new SeatGroup([
                'name' => $seatGroupData['name'],
                'number' => $seatGroupData['number'],
            ]);
            $seatGroup->save();

            $layout = json_decode($request->input('layout', '[]'), true);

            foreach ($layout as $element) {
                if ($element['group'] == $seatGroupData['id']) {
                    $element['group'] = $seatGroup->id;
                }
                $updatedLayout[] = $element;
            }
        }

        $updatedLayout = array_map(function ($element) {
            $model = match ($element['type']) {
                'seat' =>  new Seat([
                    'number' => 0,
                    'seat_group_id' => $element['group']
                ]),
                'table' => new Table([
                    'seat_group_id' => $element['group']
                ]),
            };
            $model->save();

            unset($element['group']);

            return $element;
        }, $updatedLayout);

        $hall = new Hall([
            'layout' => json_encode($updatedLayout),
        ]);
        $hall->save();
        $entertainmentVenue = EntertainmentVenue::findOrFail($request->input('entertainment-venue-id'));
        $entertainmentVenue->halls()->attach($hall->id);

        return redirect()
            ->route('admin.entertainment_venues.index')
            ->with('success', 'Hall successfully created.');
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
