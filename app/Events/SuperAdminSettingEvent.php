<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminSettingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $html;
    /**
     * Create a new event instance.
     */
    public function __construct($html)
    {
        $this->html = $html;
    }
}
