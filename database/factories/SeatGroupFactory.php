<?php

namespace Database\Factories;

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
            'session_id' => Session::get()->random()->id,
            'seat_group_id' => SeatGroup::get()->random()->id,
            'price' => $this->faker->randomDigit,
        ];
    }
}
