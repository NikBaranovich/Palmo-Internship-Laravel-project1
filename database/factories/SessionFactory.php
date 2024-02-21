<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Hall;
use Carbon\Carbon;
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
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays(7);

        $startTime = $this->faker->dateTimeBetween($startDate, $endDate);

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
