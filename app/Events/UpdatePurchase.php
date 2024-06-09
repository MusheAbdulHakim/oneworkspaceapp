<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatePurchase
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $purchase;
    public function __construct($purchase,$request)
    {
        $this->request = $request;
        $this->purchase = $purchase;
    }
}
