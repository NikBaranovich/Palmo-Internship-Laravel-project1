<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Table;
use App\Services\HallService;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function __construct(
        protected HallService $service,
        protected SeatGroup $seatGroup,
        protected Hall $hall,
        protected Seat $seat,
        protected Table $table,
    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        dd($this->service->index($request));
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
    public function store(Request $request, Hall $hall)
    {
        $hall = $this->hall->fill([
            'entertainment_venue_id' => $request->input('entertainment-venue-id'),
            'number' => $request->input('number'),
        ]);
        $hall->save();

        $seatGroups = $request->input('groups', []);
        foreach ($seatGroups as $seatGroupData) {
            $seatGroupData = json_decode($seatGroupData, true);
            // $seatGroup = $this->seatGroup->fill();
            $this->seatGroup->create([
                'id' => $seatGroupData['id'],
                'name' => $seatGroupData['name'],
                'number' => $seatGroupData['number'],
                'hall_id' => $hall->id,
                'color' => $seatGroupData['color'],
            ]);
        }
        $layout = json_decode($request->input('layout', '[]'), true);

        $layout = array_map(function ($element) {
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
            $element['id'] = $model->id;
            return $element;
        }, $layout);
        $layout = array_map(function ($element) {
            unset($element['group']);

            return $element;
        }, $layout);

        $hall->update(['layout' => json_encode($layout)]);

        return redirect()
            ->route('admin.entertainment-venues.index')
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
