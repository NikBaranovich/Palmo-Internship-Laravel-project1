<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Event\FilterRequest;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Filters\EventFilter;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()
            ->hasSessions()
            ->whereHas('sessions', function (Builder $query) use ($request) {
                $query
                    ->byStartDate($request->query('start_date'))
                    ->byCity($request->query('city'))
                    ->byVenues($request->query('venues'));
            })
            ->byGenres($request->query('genres'))
            ->topEventsByTickets($request->query('ticket_top'))
            ->byTitle($request->query('title'));

        if ($request->query('limit')) {
            $results = $query->withLimit($request->query('limit'))->get();
        } else {
            $results = $query->paginate($request->query('per_page') ?: 5);
        }

        return EventCollection::collection($results);
    }
    public function show(Event $event)
    {
        return new EventCollection($event);
    }

    public function search(Request $request)
    {
        return EventCollection::collection(Event::query()
            ->where('title', 'LIKE', "%{$request->query('title')}%")
            ->get());
    }

    public function rateEvent(Request $request, Event $event)
    {
        $request->validate([
            'vote' => 'required|integer|min:1|max:5',
        ]);

        // Create or update the rating
        $rating = Rating::updateOrCreate(
            ['user_id' => $request->user()->id, 'event_id' => $event->id],
            ['vote' => $request->vote]
        );

        return response()->json(['message' => 'Event rated successfully', 'rating' => $rating]);
    }

    public function filter(FilterRequest $request)
    {
    }

    public function getTop(Request $request)
    {
        return Event::query()
            ->topEventsByTickets($request->query('ticket_top'))
            ->get()
            ->toArray();
    }
}
