<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStatsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stats;

    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('dashboard-stats');
    }

    public function broadcastWith()
    {
        return $this->stats;
    }
}
