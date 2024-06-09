<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateResignation
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $resignation;

    public function __construct($request, $resignation)
    {
        $this->request = $request;
        $this->resignation = $resignation;
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
