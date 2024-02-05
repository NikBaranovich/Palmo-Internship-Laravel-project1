<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'overview' => $this->faker->paragraph,
            'trailer_url' => $this->faker->url,
            'poster_path' => $this->faker->imageUrl,
            'backdrop_path' => $this->faker->imageUrl,
            'release_date' => $this->faker->date,
        ];
    }
}
