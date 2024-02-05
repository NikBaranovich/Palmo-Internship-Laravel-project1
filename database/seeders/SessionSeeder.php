<?php

namespace Database\Seeders;

use App\Models\SeatGroup;
use App\Models\Session;
use App\Models\SessionSeatGroup;
use Database\Factories\SessionSeatGroupFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $sessions = Session::factory(100)->create();

        foreach ($sessions as $session) {
            $hallId = $session->hall_id;

            $seatGroups = $session->hall->seatGroups;

            foreach ($seatGroups as $seatGroup) {
                SessionSeatGroup::factory()->create([
                    'session_id' => $session->id,
                    'seat_group_id' => $seatGroup->id,
                    'price' => rand(50, 200),
                ]);
            }
        }

    }
}
