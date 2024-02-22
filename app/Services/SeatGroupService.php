<?php

namespace App\Services;

use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;

class SeatGroupService extends BaseService
{
    public function __construct()
    {
    }

    public function save($request, $hallId)
    {
        $seatGroups = $request->input('groups', []);

        foreach ($seatGroups as $seatGroupData) {
            $seatGroupData = json_decode($seatGroupData, true);

            $seatGroup = new SeatGroup();
            $seatGroup->fill($seatGroupData);
            $seatGroup->hall()->associate($hallId);

            $seatGroup->save();
        }
    }

    public function update($request, EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $seatGroups = $request->input('groups', []);

        $seatGroupIds = [];

        foreach ($seatGroups as $seatGroup) {
            $seatGroup = json_decode($seatGroup, true);
            if (isset($seatGroup['id'])) {
                $seatGroupIds[] = $seatGroup['id'];
            }
        }
        DB::beginTransaction();
        try {
            $hall->seatGroups()->whereNotIn('id', $seatGroupIds)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return ['message' => 'error'];
        }

        foreach ($seatGroups as $seatGroup) {
            $seatGroup = json_decode($seatGroup, true);

            $seatGroup = SeatGroup::updateOrCreate(
                ['id' => $seatGroup['id']],
                [
                    'name' => $seatGroup['name'],
                    'number' => $seatGroup['number'],
                    'hall_id' => $hall->id,
                    'color' => $seatGroup['color'],
                ]
            );
        }
        return ['message' => 'success'];
    }
}
