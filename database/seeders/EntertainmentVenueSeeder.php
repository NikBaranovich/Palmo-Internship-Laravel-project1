<?php

namespace Database\Seeders;

use App\Models\EntertainmentVenue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntertainmentVenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EntertainmentVenue::factory(15)->create();
    }
}
