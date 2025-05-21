<?php

namespace App\Events;

use App\Models\Claim;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClaimUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('claims');
    }

    public function broadcastWith()
    {
        return [
            'claim' => $this->claim->load(['employee', 'items']),
            'message' => 'A claim has been updated'
        ];
    }
}
