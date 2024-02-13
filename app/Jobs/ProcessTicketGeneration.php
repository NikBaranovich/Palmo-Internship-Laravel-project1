<?php

namespace App\Jobs;

use Spatie\LaravelPdf\Facades\Pdf;
use App\Events\TicketGeneration;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;

class ProcessTicketGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tickets;
    /**
     * Create a new job instance.
     */
    public function __construct($tickets)
    {
        $this->tickets = $tickets;
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
            $qrCode = QrCode::color(57, 73, 171)->errorCorrection('H')->size(70)->generate($ticket->token);

            $ticketsData[] = compact(
                'ticket',
                'seat',
                'qrCode',
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
        Browsershot::setIncludePath('$PATH:/usr/local/bin');
        $pdf = Pdf::view('pdf.ticketPdf', ['ticketsData' => $ticketsData]);
        // $pdf = FacadePdf::loadView('pdf.ticketPdf', $data);
        $file = Storage::disk('local')->put($filePath . $fileName, $pdf->output());
        $fullFileDir = Storage::path($filePath);
        chmod($fullFileDir, 0777);

        $fileUrl = $filePath . $fileName;

        event(new TicketGeneration($fileUrl));
    }
}
