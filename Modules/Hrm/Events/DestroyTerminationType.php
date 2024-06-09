<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTerminationType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $terminationtype;

    public function __construct($terminationtype)
    {
        $this->terminationtype = $terminationtype;
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
