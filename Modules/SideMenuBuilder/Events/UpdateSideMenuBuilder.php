<?php

namespace Modules\SideMenuBuilder\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSideMenuBuilder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $sidemenubuilder;

    public function __construct($request, $sidemenubuilder)
    {
        $this->request = $request;
        $this->sidemenubuilder = $sidemenubuilder;
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
