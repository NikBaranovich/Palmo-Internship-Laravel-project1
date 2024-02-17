<?php

namespace App\Jobs;

use App\Events\EmailNotification;
use App\Events\TicketGeneration;
use App\Mail\OrderProcessed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProcessEmailTicketNotification implements ShouldQueue
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
    public function handle(): void
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

        Mail::to($this->user->email)->send(new OrderProcessed($ticketsData));
        event(new EmailNotification('Tickets have been sent to your email', $this->user));
    }
}
