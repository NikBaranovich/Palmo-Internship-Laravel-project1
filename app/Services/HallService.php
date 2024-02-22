<?php

namespace App\Services;

use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Table;
use App\Repositories\HallRepository;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HallService extends BaseService
{
    public function __construct(
        protected HallRepository $repository,
        protected SeatService $seatService,
        protected SeatGroupService $seatGroupService,
        protected Hall $hall,
    ) {
    }

    public function index(Request $request)
    {
        return $this->repository->index($request, $request->entertainmentVenue);
    }

    public function save($request, EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $hall->fill([
            'number' => $request->input('number'),
        ]);
        $hall->entertainmentVenue()->associate($entertainmentVenue->id);
        $hall->save();

        $this->seatGroupService->save($request, $hall->id);

        $layout = $this->seatService->save($request);

        $hall->update(['layout' => json_encode($layout)]);
    }

    public function update($request, EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $hall->update([
            'number' => $request->input('number'),
        ]);

        $response = $this->seatGroupService->update($request, $entertainmentVenue, $hall);

        if (is_array($response) && $response['message'] === 'error') {
            return [
                'status' => 'error',
                'message' => 'One or more deleted groups had sessions associated with them.'
            ];
        }

        $layout = $this->seatService->update($request, $entertainmentVenue, $hall);

        if (!$layout) {
            return [
                'status' => 'error',
                'message' => 'One or more deleted seats had tickets associated with them.'
            ];
        }
        $hall->update(['layout' => json_encode($layout)]);

        return [
            'status' => 'success',
            'message' => 'Hall successfully updated.'
        ];
    }

    public function delete(Hall $hall)
    {
        DB::beginTransaction();
        try {
            $hall->seatGroups()->delete();
            $hall->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'One or more deleted seats had tickets associated with them.'
            ];
        }
        return [
            'status' => 'success',
            'message' => 'Hall successfully deleted.'
        ];
    }
}
