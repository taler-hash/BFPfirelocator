<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class BookingMapUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $coords;
    public $booking;

    /**
     * Create a new event instance.
     */
    public function __construct($booking, $coords)
    {
        // Tiwasa ipasa nalang ang booking tanan para ig i cancel ma notify ang uban buhatan nimug callback sa ui
        $this->coords = $coords;
        $this->booking = $booking;
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('booking.'.$this->booking['id']);
    }
}
