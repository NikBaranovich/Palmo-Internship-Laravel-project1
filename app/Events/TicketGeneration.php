<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketGeneration implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $url;
    /**
     * Create a new event instance.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('ticket-generation'),
        ];
    }

    public function broadcastWith(): array
    {
        return ['url' => $this->url];
    }
}
