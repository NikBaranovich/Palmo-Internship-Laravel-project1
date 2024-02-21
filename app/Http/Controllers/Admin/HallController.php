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

    public function store(UpdateHallRequest $request, Hall $hall)
    {
        $hall = new Hall([
            'entertainment_venue_id' => $request->input('entertainment-venue-id'),
            'number' => $request->input('number'),
        ]);
        $hall->save();

        $seatGroups = $request->input('groups', []);
        foreach ($seatGroups as $seatGroupData) {
            $seatGroupData = json_decode($seatGroupData, true);
            $seatGroup = new SeatGroup([
                'id' => $seatGroupData['id'],
                'name' => $seatGroupData['name'],
                'number' => $seatGroupData['number'],
                'hall_id' => $hall->id,
                'color' => $seatGroupData['color'],
            ]);
            $seatGroup->save();
        }

        $layout = json_decode($request->input('layout', '[]'), true);

        $layout = array_map(function ($element) {
            $model = match ($element['type']) {
                'seat' =>  new Seat([
                    'number' => $element['number'],
                    'seat_group_id' => $element['group']
                ]),
                'table' => new Table([
                    'seat_group_id' => $element['group']
                ]),
                default => null,
            };
            if ($model) {
                $model->save();
                $element['id'] = $model->id;
            }
            return $element;
        }, $layout);
        $layout = array_map(function ($element) {
            unset($element['group']);
            unset($element['color']);
            unset($element['number']);

            return $element;
        }, $layout);

        $hall->update(['layout' => json_encode($layout)]);

        $entertainmentVenue = $request->input('entertainment-venue-id');
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
        $hall->update([
            'number' => $request->input('number'),
        ]);

        $seatGroupsData = $request->input('groups', []);


        $receivedSeatGroupIds = [];
        foreach ($seatGroupsData as $seatGroupData) {
            $decodedData = json_decode($seatGroupData, true);
            if (isset($decodedData['id'])) {
                $receivedSeatGroupIds[] = $decodedData['id'];
            }
        }
        try {
            $hall->seatGroups()->whereNotIn('id', $receivedSeatGroupIds)->delete();
        } catch (Exception $e) {

            return redirect()
                ->route('admin.halls.index', compact('entertainmentVenue'))
                ->with('error', 'One or more deleted groups had tickets associated with them.');
        }

        foreach ($seatGroupsData as $seatGroupData) {
            $seatGroupData = json_decode($seatGroupData, true);

            $seatGroup = SeatGroup::updateOrCreate(
                ['id' => $seatGroupData['id']],
                [
                    'name' => $seatGroupData['name'],
                    'number' => $seatGroupData['number'],
                    'hall_id' => $hall->id,
                    'color' => $seatGroupData['color'],
                ]
            );
        }


        //elements
        $layout = json_decode($request->input('layout', '[]'), true);

        $newElementIds = collect($layout)->pluck('id')->filter();

        $seatGroups = $hall->seatGroups;

        foreach ($seatGroups as $seatGroup) {
            $existingSeatIds = $seatGroup->seats()->pluck('id')->toArray();
            $existingTableIds = $seatGroup->tables()->pluck('id')->toArray();

            $seatsToDelete = array_diff($existingSeatIds, $newElementIds->toArray());
            $tablesToDelete = array_diff($existingTableIds, $newElementIds->toArray());

            try {
                Seat::destroy($seatsToDelete);
                Table::destroy($tablesToDelete);
            } catch (Exception $e) {

                return redirect()
                    ->route('admin.halls.index', compact('entertainmentVenue'))
                    ->with('error', 'One or more deleted elements had tickets associated with them.');
            }
        }

        try {
            $layout = array_map(function ($element) use ($hall, $entertainmentVenue) {
                $model = match ($element['type']) {
                    'seat' => Seat::updateOrCreate(
                        ['id' => $element['id'] ?? null],
                        [
                            'number' => $element['number'],
                            'seat_group_id' => $element['group']
                        ]
                    ),
                    'table' => Table::updateOrCreate(
                        ['id' => $element['id'] ?? null],
                        [
                            'seat_group_id' => $element['group']
                        ]
                    ),
                    default => null,
                };

                if ($model) {
                    $element['id'] = $model->id;
                }

                return $element;
            }, $layout);
        } catch (Exception $e) {

            return redirect()
                ->route('admin.halls.index', compact('entertainmentVenue'))
                ->with('error', 'An error occurred, hall was not updated');
        }

        $layout = array_map(function ($element) {
            unset($element['group']);
            unset($element['color']);
            unset($element['number']);

            return $element;
        }, $layout);

        $hall->update(['layout' => json_encode($layout)]);

        return redirect()
            ->route('admin.halls.index', compact('entertainmentVenue'))
            ->with('success', 'Hall successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
