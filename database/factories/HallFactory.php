<?php

namespace Database\Factories;

use App\Models\EntertainmentVenue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hall>
 */
class HallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(1, 5),
            'entertainment_venue_id' => EntertainmentVenue::get()->random()->id,
        ];
    }
}
