<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DefaultData
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $company_id;
    public $workspace_id;
    public $user_module;

    public function __construct($company_id,$workspace_id= null,$user_module = null)
    {
        $this->company_id = $company_id;
        $this->workspace_id = $workspace_id;
        $this->user_module = explode(',',$user_module);
    }
}
