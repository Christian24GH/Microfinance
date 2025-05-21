<?php

namespace App\Events;

use App\Models\Shift;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShiftUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shift;

    public function __construct(Shift $shift)
    {
        $this->shift = $shift;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('shifts');
    }

    public function broadcastWith()
    {
        return [
            'shift' => $this->shift,
            'message' => 'A shift has been updated'
        ];
    }
}
