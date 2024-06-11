<?php

namespace Modules\SideMenuBuilder\Events;

use Illuminate\Queue\SerializesModels;

class DestroySideMenuBuilder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $menu_builder;

    public function __construct($menu_builder)
    {
        $this->menu_builder = $menu_builder;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
