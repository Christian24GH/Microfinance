<?php

namespace App\Events;

use App\Models\LeaveRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('leave-requests');
    }

    public function broadcastWith()
    {
        return [
            'leave_request' => $this->leaveRequest->load(['employee', 'leaveType']),
            'message' => 'A new leave request has been submitted'
        ];
    }
}
