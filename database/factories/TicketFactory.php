<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $session = Session::get()->random();
        $sessionSeatGroup = $session->sessionSeatGroups->random();
        return [
            'user_id' => User::get()->random()->id,
            'token' => Str::random(10),
            'session_id' => $session->id,
            'seat_id' => $sessionSeatGroup->seatGroup->seats->random()->id,
            'price' => $sessionSeatGroup->price,
        ];
    }
}
