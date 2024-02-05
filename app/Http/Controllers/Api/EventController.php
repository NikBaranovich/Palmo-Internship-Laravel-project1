<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        return EventCollection::collection(Event::query()->hasSessions()->get());
    }
    public function show(Event $event)
    {
    }

    public function search(Request $request)
    {
        return EventCollection::collection(Event::query()
            ->where('title', 'LIKE', "%{$request->query('title')}%")
            ->get());
    }

    public function filter(Request $request)
    {
        $startDate = Carbon::parse($request->query('start_date'));

        $endDate = clone $startDate;
        $endDate->addDay();

        $dateRange = [
            $startDate,
            $endDate
        ];
        // dd($dateRange);
        $venues = $request->query('venues');
        $genres = $request->query('genres');
        // dump($request->query('start_date'));
        // dump(array_column($request->query('venues'), 'id'));

        return EventCollection::collection(Event::query()
            ->hasSessions()
            ->whereHas('sessions', function (Builder $query) use ($request, $dateRange, $venues) {
                $query
                    ->when($request->query('start_date'), function (Builder $query) use ($dateRange) {
                        $query->whereDate($query->qualifyColumn('start_time'), $dateRange);
                    })
                    ->when($request->query('venues'), function (Builder $query) use ($venues){
                        $query->whereHas('hall.entertainmentVenue', function (Builder $query) use ($venues) {
                            $query->whereIn($query->qualifyColumn('id'), array_column($venues, 'id'));
                        });
                    });
            })
            ->when($request->query('genres'), function (Builder $query) use ($genres){
                $query->whereHas('genres', function (Builder $query) use ($genres) {
                    $query->whereIn($query->qualifyColumn('id'), array_column($genres, 'id'));
                });
            })
            ->get());
    }

    public function getTop(Request $request)
    {
        return Event::query()
            ->topEventsByTickets($request->query('limit'))
            ->get()
            ->toArray();
    }
}
