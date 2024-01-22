<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use App\Models\Session;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function search(Request $request)
    {
        return EventCollection::collection(Event::query()
            ->where('name', 'LIKE', "%{$request->query('name')}%")
            ->get());
    }

    public function getTop(Request $request)
    {
        return Event::query()
            ->topEventsByTickets(request()->query('limit'))
            ->get(['id', 'name', 'tickets_count'])
            ->toArray();
    }
}
