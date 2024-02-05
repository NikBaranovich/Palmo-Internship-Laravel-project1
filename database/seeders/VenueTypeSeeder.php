<?php

namespace Database\Seeders;

use App\Models\VenueType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Cinema', 'Theater',
        ];

        foreach ($types as $type) {
            VenueType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
