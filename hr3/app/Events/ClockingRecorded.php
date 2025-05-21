<?php

namespace App\Events;

use App\Models\ClockingRecord;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClockingRecorded implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $record;

    public function __construct(ClockingRecord $record)
    {
        $this->record = $record;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('clocking');
    }

    public function broadcastWith()
    {
        return [
            'record' => $this->record->load('employee'),
            'message' => 'A new clocking record has been recorded'
        ];
    }
}
