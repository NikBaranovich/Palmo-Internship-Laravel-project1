<?php

namespace Database\Factories;

use App\Models\Hall;
use App\Models\SeatGroup;
use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeatGroup>
 */
class SeatGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'color' => $this->faker->rgbColor(),
            'number' => $this->faker->numberBetween(1, 5),
            'hall_id' => Hall::get()->random()->id,
        ];
    }
}
