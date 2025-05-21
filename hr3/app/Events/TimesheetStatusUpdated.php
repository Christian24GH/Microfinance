<?php

namespace App\Events;

use App\Models\Timesheet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimesheetStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timesheet;

    public function __construct(Timesheet $timesheet)
    {
        $this->timesheet = $timesheet;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('timesheet.approvals');
    }

    public function broadcastWith()
    {
        return [
            'timesheet' => $this->timesheet->load(['employee', 'entries'])
        ];
    }
}
