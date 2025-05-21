<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClaimDeleted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $claimId;

    public function __construct($claimId)
    {
        $this->claimId = $claimId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('claims');
    }

    public function broadcastWith()
    {
        return [
            'claim_id' => $this->claimId,
            'message' => 'A claim has been deleted'
        ];
    }
}
