<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function search(Request $request)
    {
        return EventCollection::collection(Event::query()
            ->where('name', 'LIKE', "%{$request->query('name')}%")
            ->get());
    }
}
