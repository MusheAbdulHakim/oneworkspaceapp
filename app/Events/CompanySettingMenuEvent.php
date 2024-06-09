<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanySettingMenuEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $menu;
    /**
     * Create a new event instance.
     */
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}
