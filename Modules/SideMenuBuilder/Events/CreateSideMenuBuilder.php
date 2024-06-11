<?php

namespace Modules\SideMenuBuilder\Events;

use Illuminate\Queue\SerializesModels;

class CreateSideMenuBuilder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $menu_builder;

    public function __construct($request, $menu_builder)
    {
        $this->request = $request;
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
