<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Hall;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->unique()->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null);
        $endTime = clone $startTime;
        $endTime->add(new DateInterval('PT2H'));
        return [
            'hall_id' => Hall::get()->random()->id,
            'event_id' => Event::get()->random()->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
