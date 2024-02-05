<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Film', 'Concert', 'Theater', 'StandUp',
        ];
        foreach ($types as $type) {
            EventType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
