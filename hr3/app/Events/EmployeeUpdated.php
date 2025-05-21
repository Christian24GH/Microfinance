<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;

class EmployeeUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('employees');
    }

    public function broadcastWith()
    {
        return [
            'employee' => $this->employee->load(['department', 'position']),
            'message' => 'Employee information has been updated'
        ];
    }
}
