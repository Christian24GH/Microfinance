<?php

namespace App\Events;

use App\Models\TimeEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimeEntryUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timeEntry;

    public function __construct(TimeEntry $timeEntry)
    {
        $this->timeEntry = $timeEntry;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('time-entries');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->timeEntry->id,
            'employee_id' => $this->timeEntry->employee_id,
            'date' => $this->timeEntry->formatted_date,
            'time_in' => $this->timeEntry->formatted_time_in,
            'time_out' => $this->timeEntry->formatted_time_out,
            'total_hours' => $this->timeEntry->total_hours,
            'status' => $this->timeEntry->status,
            'notes' => $this->timeEntry->notes
        ];
    }
}
