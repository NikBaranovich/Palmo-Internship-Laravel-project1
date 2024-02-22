<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Mail\OrderProcessed;
use App\Models\EntertainmentVenue;
use App\Models\Event;
use App\Models\Session;
use App\Models\SessionSeatGroup;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

class TicketController extends Controller
{
    public function __construct(
        protected Ticket $ticket,
        protected SessionSeatGroup $sessionSeatGroup
    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tickets = $this->ticket->query()
            ->when($request->has('sort_by'), function (Builder $query) use ($request) {
                $query->orderBy(
                    $request->input('sort_by'),
                    $request->input('sort_order', 'asc')
                );
            })
            ->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Ticket $ticket)
    {
        $sessions = Session::all();
        return view('admin.ticket.edit', compact('ticket', 'sessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $data['price'] = $this->sessionSeatGroup->session_seat_price;

        $data['token'] = (string) Str::uuid();


        $this->ticket->create($data);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
