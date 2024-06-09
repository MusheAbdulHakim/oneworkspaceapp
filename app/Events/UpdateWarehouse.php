<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateWarehouse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $warehouse;
    public function __construct($warehouse,$request)
    {
        $this->request = $request;
        $this->warehouse = $warehouse;
    }
}
