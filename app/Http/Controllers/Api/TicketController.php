<?php

namespace App\Http\Controllers\Api;

use App\Events\TicketGeneration;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmailTicketNotification;
use App\Jobs\ProcessTicketGeneration;
use App\Models\Seat;
use App\Models\Session;
use App\Models\SessionSeatGroup;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct(
        protected Ticket $ticket
    ) {
    }

    public function getTicketsCountByMonth()
    {
        $endDate = Carbon::now();

        $startDate = $endDate->copy()->subMonths(6);

        $results = Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('count(*) as count, MONTH(created_at) as month, YEAR(created_at) as year ')
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse("$item->year-$item->month-01")->format('M Y') => $item->count];
            });

        $monthsRange = collect(Carbon::parse($startDate)->startOfMonth()->monthsUntil(Carbon::parse($endDate)->endOfMonth()));

        return $monthsRange->mapWithKeys(function ($month) use ($results) {
            $formattedMonth = $month->format('M Y');
            return [$formattedMonth => $results[$formattedMonth] ?? 0];
        });
    }

    public function processOrder(Request $request)
    {
        $tickets = [];
        foreach ($request->input('tickets') as $ticket) {
            $price = SessionSeatGroup::query()->where('session_id', $request
                ->input('session_id'))
                ->where('seat_group_id', Seat::find($ticket['id'])->seat_group_id)
                ->value('price');
            $dbTicket = new Ticket();
            $dbTicket->fill([
                'user_id' => $request->user()->id,
                'session_id' => $request->input('session_id'),
                'token' => Str::uuid(),
                'price' => $price,
                'seat_id' => $ticket['id'],
            ]);
            $tickets[] = $dbTicket;
            $dbTicket->save();
        }
        //pdf generation
        ProcessTicketGeneration::dispatch($tickets, $request->user());

        ProcessEmailTicketNotification::dispatch($tickets, $request->user());
    }

    public function download(Request $request)
    {
        if (Auth::user()) {
            $headers = array(
                'Content-Type: application/pdf',
            );
            $path = storage_path('app/' . $request
                ->input('filepath'));
            return response()->download($path, 'filename.pdf', $headers, 'inline');
        } else {
            return abort('403');
        }
    }
}
