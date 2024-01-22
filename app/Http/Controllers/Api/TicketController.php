<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
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
}
