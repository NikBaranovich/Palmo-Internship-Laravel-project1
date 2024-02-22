<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHallRequest;
use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Table;
use App\Services\HallService;
use Exception;
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
    public function index(Request $request, EntertainmentVenue $entertainmentVenue)
    {
        $halls = $this->service->index($request);


        return view('admin.halls.index', compact('halls', 'entertainmentVenue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        return view('admin.halls.edit', compact('hall', 'entertainmentVenue'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateHallRequest $request, EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $this->service->save($request, $entertainmentVenue, $hall);

        return redirect()
            ->route('admin.halls.index', compact('entertainmentVenue'))
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
    public function edit(EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $hallItems = json_decode($hall->layout);
        foreach ($hallItems as $item) {
            switch ($item->type) {
                case 'seat':
                    $element = Seat::findOrFail($item->id);
                    $item->number = $element->number;
                    $item->group = $element->seat_group_id;
                    $item->color = $element->seatGroup->color;
                    break;
                case 'table':
                    $element = Table::findOrFail($item->id);
                    $item->group = $element->seat_group_id;
                    $item->color = $element->seatGroup->color;
                    break;
            }
        }
        $hallItems = json_encode($hallItems);
        return view('admin.halls.edit', compact('hall', 'entertainmentVenue', 'hallItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $response = $this->service->update($request, $entertainmentVenue, $hall);

        return redirect()
            ->route('admin.halls.index', compact('entertainmentVenue'))
            ->with($response['status'], $response['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $response =$this->service->delete($hall);

        return redirect()
            ->route('admin.halls.index', compact('entertainmentVenue'))
            ->with($response['status'], $response['message']);
    }
}
