<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GivePermissionToRole
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $rolename;
    public $role_id;
    public $user_module;

    public function __construct($role_id,$rolename,$user_module = null)
    {
        $this->role_id = $role_id;
        $this->rolename = $rolename;
        $this->user_module = explode(',',$user_module);
    }
}
