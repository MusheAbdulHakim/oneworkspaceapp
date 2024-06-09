<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyResignation
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $resignation;

    public function __construct($resignation)
    {
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
