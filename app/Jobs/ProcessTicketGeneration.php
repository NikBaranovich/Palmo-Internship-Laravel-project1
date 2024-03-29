<?php

namespace App\Jobs;

use Spatie\LaravelPdf\Facades\Pdf;
use App\Events\TicketGeneration;
use App\Mail\OrderProcessed;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;

class ProcessTicketGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tickets;
    private $user;
    /**
     * Create a new job instance.
     */
    public function __construct($tickets, $user)
    {
        $this->tickets = $tickets;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $ticketsData = [];
        foreach ($this->tickets as $ticket) {
            $seat = $ticket->seat;
            $hall = $seat->seatGroup->hall;

            $entertainmentVenue = $hall->entertainmentVenue;
            $seatGroup = $seat->seatGroup;

            $session = $ticket->session;
            $event = $session->event;

            $ticketsData[] = compact(
                'ticket',
                'seat',
                'event',
                'entertainmentVenue',
                'hall',
                'session',
                'seatGroup'
            );
        }
        $data = [
            'ticketsData' => $ticketsData,
        ];
        $filePath = 'tickets/';
        $fileName = 'tickets-103.pdf';

        $pdf = FacadePdf::loadView('pdf.ticketPdf', $data);
        $file = Storage::disk('local')->put($filePath . $fileName, $pdf->output());
        $fullFileDir = Storage::path($filePath);
        chmod($fullFileDir, 0777);

        $fileUrl = $filePath . $fileName;

        event(new TicketGeneration($fileUrl, $this->user));

    }
}
