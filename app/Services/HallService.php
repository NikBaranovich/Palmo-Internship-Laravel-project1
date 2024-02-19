<?php

namespace App\Services;

use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Table;
use App\Repositories\HallRepository;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class HallService extends BaseService
{
    public function __construct(
        protected HallRepository $repository,
        protected Hall $hall,
        protected Seat $seat,
        protected Table $table,
        protected SeatGroup $seatGroup,
        protected EntertainmentVenue $entertainmentVenue,
    ) {
    }

    public function index(Request $request)
    {
        return $this->repository->index($request, $request->entertainmentVenue);
    }

    public function save($request, Hall $hall)
    {
        //todo refactor simplify

        $hall = $this->hall->fill([
            'entertainment_venue_id' => $request->input('entertainment-venue-id'),
            'number' => $request->input('number'),
        ]);
        $hall->save();

        $seatGroups = $request->input('groups', []);
        foreach ($seatGroups as $seatGroupData) {
            $seatGroupData = json_decode($seatGroupData, true);
            $seatGroup = $this->seatGroup->fill([
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
                'seat' =>  $this->seat->fill([
                    'number' => 0,
                    'seat_group_id' => $element['group']
                ]),
                'table' => $this->table->fill([
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

        return $hall->update(['layout' => json_encode($layout)]);
    }

    public function delete(Hall $hall)
    {
        return $hall->delete();
    }
}
