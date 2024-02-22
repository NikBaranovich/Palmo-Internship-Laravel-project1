<?php

namespace Database\Seeders;

use App\Models\EntertainmentVenue;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatGroup;
use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hallLayouts = [
            [
                'layout' =>  '[ { "x": "0", "y": "0", "type": "scene", "width": "500", "height": "30" }, { "x": "175", "y": "60", "type": "seat", "number": "1", "group": "1", "width": "20", "height": "20" }, { "x": "205", "y": "60", "type": "seat", "number": "2", "group": "1", "width": "20", "height": "20" }, { "x": "235", "y": "60", "type": "seat", "number": "3", "group": "1", "width": "20", "height": "20" }, { "x": "265", "y": "60", "type": "seat", "number": "4", "group": "1", "width": "20", "height": "20" }, { "x": "295", "y": "60", "type": "seat", "number": "5", "group": "1", "width": "20", "height": "20" }, { "x": "175", "y": "90", "type": "seat", "number": "1", "group": "2", "width": "20", "height": "20" }, { "x": "205", "y": "90", "type": "seat", "number": "2", "group": "2", "width": "20", "height": "20" }, { "x": "235", "y": "90", "type": "seat", "number": "3", "group": "2", "width": "20", "height": "20" }, { "x": "265", "y": "90", "type": "seat", "number": "4", "group": "2", "width": "20", "height": "20" }, { "x": "295", "y": "90", "type": "seat", "number": "5", "group": "2", "width": "20", "height": "20" }, { "x": "175", "y": "120", "type": "seat", "number": "1", "group": "3", "width": "20", "height": "20" }, { "x": "205", "y": "120", "type": "seat", "number": "2", "group": "3", "width": "20", "height": "20" }, { "x": "235", "y": "120", "type": "seat", "number": "3", "group": "3", "width": "20", "height": "20" }, { "x": "265", "y": "120", "type": "seat", "number": "4", "group": "3", "width": "20", "height": "20" }, { "x": "295", "y": "120", "type": "seat", "number": "5", "group": "3", "width": "20", "height": "20" }, { "x": "175", "y": "155", "type": "seat", "number": "1", "group": "4", "width": "20", "height": "20" }, { "x": "205", "y": "155", "type": "seat", "number": "2", "group": "4", "width": "20", "height": "20" }, { "x": "295", "y": "155", "type": "seat", "number": "3", "group": "4", "width": "20", "height": "20" } ]',
                'groups' => [
                    [
                        'id' => 1,
                        'name' => 'Row',
                        'number' => 1,
                        'color' => '#d52020',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Row',
                        'number' => 2,
                        'color' => '#8e28bd',
                    ],
                    [
                        'id' => 3,
                        'name' => 'Row',
                        'number' => 3,
                        'color' => '#379a48',
                    ],
                    [
                        'id' => 4,
                        'name' => 'Parterre',
                        'number' => 1,
                        'color' => '#d59320',
                    ],
                ]
            ],
            [
                'layout' =>  '[ { "x": "0", "y": "0", "type": "scene", "width": "500", "height": "30" }, { "x": "70", "y": "70", "group": "1", "type": "table", "width": "20", "height": "20" }, { "x": "80", "y": "45", "group": "1", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "115", "y": "80", "group": "1", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "80", "y": "115", "group": "1", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "45", "y": "80", "group": "1", "number": "4", "type": "seat", "width": "20", "height": "20" }, { "x": "175", "y": "70", "group": "2", "type": "table", "width": "20", "height": "20" }, { "x": "185", "y": "45", "group": "2", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "220", "y": "80", "group": "2", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "185", "y": "115", "group": "2", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "150", "y": "80", "group": "2", "number": "4", "type": "seat", "width": "20", "height": "20" }, { "x": "280", "y": "70", "group": "3", "type": "table", "width": "20", "height": "20" }, { "x": "290", "y": "45", "group": "3", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "325", "y": "80", "group": "3", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "290", "y": "115", "group": "3", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "255", "y": "80", "group": "3", "number": "4", "type": "seat", "width": "20", "height": "20" }, { "x": "385", "y": "70", "group": "4", "type": "table", "width": "20", "height": "20" }, { "x": "395", "y": "45", "group": "4", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "430", "y": "80", "group": "4", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "395", "y": "115", "group": "4", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "360", "y": "80", "group": "4", "number": "4", "type": "seat", "width": "20", "height": "20" } ]',
                'groups' => [
                    [
                        'id' => 1,
                        'name' => 'Table',
                        'number' => 1,
                        'color' => '#d52020',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Table',
                        'number' => 2,
                        'color' => '#8e28bd',
                    ],
                    [
                        'id' => 3,
                        'name' => 'Table',
                        'number' => 3,
                        'color' => '#379a48',
                    ],
                    [
                        'id' => 4,
                        'name' => 'Table',
                        'number' => 4,
                        'color' => '#d59320',
                    ],
                ]
            ],
            [
                'layout' =>  '[ { "x": "0", "y": "0", "type": "scene", "width": "500", "height": "35" }, { "x": "165", "y": "75", "group": "1", "type": "table", "width": "20", "height": "20" }, { "x": "175", "y": "50", "group": "1", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "210", "y": "85", "group": "1", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "175", "y": "120", "group": "1", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "140", "y": "85", "group": "1", "number": "4", "type": "seat", "width": "20", "height": "20" }, { "x": "270", "y": "75", "group": "2", "type": "table", "width": "20", "height": "20" }, { "x": "280", "y": "50", "group": "2", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "315", "y": "85", "group": "2", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "280", "y": "120", "group": "2", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "245", "y": "85", "group": "2", "number": "4", "type": "seat", "width": "20", "height": "20" }, { "x": "170", "y": "155", "group": "3", "number": "1", "type": "seat", "width": "20", "height": "20" }, { "x": "200", "y": "155", "group": "3", "number": "2", "type": "seat", "width": "20", "height": "20" }, { "x": "230", "y": "155", "group": "3", "number": "3", "type": "seat", "width": "20", "height": "20" }, { "x": "260", "y": "155", "group": "3", "number": "4", "type": "seat", "width": "20", "height": "20" }, { "x": "290", "y": "155", "group": "3", "number": "5", "type": "seat", "width": "20", "height": "20" }, { "x": "105", "y": "50", "group": "4", "number": "1", "type": "seat", "width": "20", "height": "55" }, { "x": "105", "y": "115", "group": "4", "number": "2", "type": "seat", "width": "20", "height": "55" }, { "x": "350", "y": "50", "group": "5", "number": "1", "type": "seat", "width": "20", "height": "55" }, { "x": "350", "y": "115", "group": "5", "number": "2", "type": "seat", "width": "20", "height": "55" } ]',
                'groups' => [
                    [
                        'id' => 1,
                        'name' => 'Table',
                        'number' => 1,
                        'color' => '#d52020',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Table',
                        'number' => 2,
                        'color' => '#8e28bd',
                    ],
                    [
                        'id' => 3,
                        'name' => 'Row',
                        'number' => 1,
                        'color' => '#379a48',
                    ],
                    [
                        'id' => 4,
                        'name' => 'Side',
                        'number' => 1,
                        'color' => '#d59320',
                    ],
                    [
                        'id' => 5,
                        'name' => 'Side',
                        'number' => 2,
                        'color' => '#1b87cf',
                    ],
                ]
            ]
        ];

        foreach (EntertainmentVenue::get() as $entertainmentVenue) {
            $hallCounter = 1;
            foreach ($hallLayouts as $layoutData) {
                $hall = Hall::factory()->create([
                    'entertainment_venue_id' => $entertainmentVenue->id,
                    'number' => $hallCounter,
                ]);
                $hallCounter++;

                $hallLayout = json_decode($layoutData['layout'], true);
                $seatGroups = $layoutData['groups'];
                $groupPrefixId = uniqid();
                $groupCounter = 1;

                foreach ($seatGroups as $key => $group) {
                    $seatGroups[$key]['newId'] = $groupPrefixId . $groupCounter;
                    SeatGroup::factory()->create([
                        'id' => $groupPrefixId . $groupCounter,
                        'name' => $group['name'],
                        'number' => $group['number'],
                        'color' => $group['color'],
                        'hall_id' => $hall->id,

                    ]);
                    $groupCounter++;
                }
                $layout = array_map(function ($element) use ($seatGroups) {
                    if ($element['type'] == 'scene') {
                        return $element;
                    }
                    $groupIndex = array_search($element['group'], array_column($seatGroups, 'id'));

                    if ($groupIndex !== false) {
                        $element['group'] = $seatGroups[$groupIndex]['newId'];
                    }

                    $model = match ($element['type']) {
                        'seat' =>   Seat::factory()->create([
                            'number' => $element['number'],
                            'seat_group_id' => $element['group'],
                        ]),
                        'table' => Table::factory()->create([
                            'seat_group_id' => $element['group'],
                        ]),
                        default => null,
                    };

                    if ($model) {
                        $element['id'] = $model->id;
                    }

                    unset($element['group'], $element['number']);

                    return $element;
                }, $hallLayout);

                $hall->update(['layout' => json_encode($layout)]);
            }
        }
    }
}
