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
    private $user;
    /**
     * Create a new event instance.
     */
    public function __construct($url, $user)
    {
        $this->url = $url;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user-' . $this->user->id),
        ];
    }

    public function broadcastWith(): array
    {
        return ['url' => $this->url];
    }

    public function broadcastAs()
    {
        return 'ticket-generation';
    }
}
