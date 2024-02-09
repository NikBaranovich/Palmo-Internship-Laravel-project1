<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\VenueType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EntertainmentVenue>
 */
class EntertainmentVenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'city_id' => City::get()->random()->id,
            'address' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'venue_type_id' => VenueType::get()->random()->id,
        ];
    }
}
