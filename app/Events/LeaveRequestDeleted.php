<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestDeleted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $leaveRequestId;

    public function __construct($leaveRequestId)
    {
        $this->leaveRequestId = $leaveRequestId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('leave-requests');
    }

    public function broadcastWith()
    {
        return [
            'leave_request_id' => $this->leaveRequestId,
            'message' => 'A leave request has been deleted'
        ];
    }
}
