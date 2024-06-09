<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTermination
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $termination;

    public function __construct($termination)
    {
        $this->termination = $termination;
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
