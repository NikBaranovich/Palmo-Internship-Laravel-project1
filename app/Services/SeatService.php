<?php

namespace App\Services;

use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\Table;
use Exception;
use Illuminate\Support\Facades\DB;

class SeatService extends BaseService
{
    public function save($request)
    {
        $layout = json_decode($request->input('layout', '[]'), true);

        $layout = array_map(function ($element) {
            $model = match ($element['type']) {
                'seat' =>  new Seat($element),
                'table' => new Table(),
                default => null,
            };

            if ($model) {
                $model->seatGroup()->associate($element['group']);
                $model->save();

                $element['id'] = $model->id;
            }

            unset($element['group'], $element['color'], $element['number']);

            return $element;
        }, $layout);

        return $layout;
    }

    public function update($request, EntertainmentVenue $entertainmentVenue, Hall $hall)
    {
        $layout = json_decode($request->input('layout', '[]'), true);

        $newSeats = [];
        $newTables = [];

        foreach ($layout as $element) {
            if ($element['type'] === 'seat') {
                $newSeats[] = $element['id'];
            } elseif ($element['type'] === 'table') {
                $newTables[] = $element['id'];
            }
        }
        DB::beginTransaction();
        try {
            foreach ($hall->seatGroups as $seatGroup) {
                try {
                    $seatGroup->seats()->whereNotIn('id', $newSeats)->delete();
                    $seatGroup->tables()->whereNotIn('id', $newTables)->delete();
                } catch (Exception $e) {
                    return false;
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        try {
            $layout = array_map(function ($element) {
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
                unset($element['group'], $element['color'], $element['number']);

                return $element;
            }, $layout);
        } catch (Exception $e) {

            return false;
        }

        return $layout;
    }
}
