<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EntertainmentVenue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Database\Seeders\EventSeeder;
use App\Models\VenueType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // AdminSeeder::class,
            // UserSeeder::class,

            // EventTypeSeeder::class,
            // GenreSeeder::class,
            // EventSeeder::class,
            // RatingSeeder::class,

            // CitySeeder::class,
            // VenueTypeSeeder::class,
            // EntertainmentVenueSeeder::class,
            // HallSeeder::class,

            SessionSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
