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
    public function __construct(protected Rating $rating)
    {
        // $this->middleware('web');
    }

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
            ->topEventsByViews($request->query('views_top'))
            ->topEventsByRate($request->query('rate_top'))
            ->topEventsByUser($request->query('user'))
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
        return Event::query()
            ->where('title', 'LIKE', "%{$request->query('title')}%")
            ->get(['id', 'title']);
    }

    public function rateEvent(Request $request, string $event)
    {
        $request->validate([
            'vote' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::where('user_id', $request->user()->id)->where('event_id', $event)->first();
        if ($rating) {
            $this->rating = $rating;
        }
        $this->rating->vote = $request->input('vote');
        $this->rating->user()->associate($request->user()->id);
        $this->rating->event()->associate($event);

        $this->rating->save();

        return;
    }
    public function getUserRating(Request $request, string $event)
    {
        $rating = Rating::where('user_id', $request->user()->id)->where('event_id', $event)->first();

        return response()->json(['vote' => $rating?->vote ?: 0]);
    }

    public function getTop(Request $request)
    {
        return Event::query()
            ->topEventsByTickets($request->query('ticket_top'))
            ->get()
            ->toArray();
    }

    public function incrementViews(Event $event)
    {
        $event->increment('views_count');
        return response()->json([]);
    }
}
