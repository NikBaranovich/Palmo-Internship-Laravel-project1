<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Models\EntertainmentVenue;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:' . UserRole::Admin->value);
    }

    public function show()
    {
        $venuesCount = EntertainmentVenue::query()->count();
        $eventsCount = Event::query()->count();
        $usersCount = User::query()->count();
        $ticketsCount = Ticket::query()->count();

        return view(
            'admin.dashboard',
            compact(
                'venuesCount',
                'eventsCount',
                'usersCount',
                'ticketsCount'
            )
        );
    }
}
