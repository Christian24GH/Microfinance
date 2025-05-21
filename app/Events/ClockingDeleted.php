<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClockingDeleted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $recordId;

    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('clocking');
    }

    public function broadcastWith()
    {
        return [
            'record_id' => $this->recordId,
            'message' => 'A clocking record has been deleted'
        ];
    }
}
