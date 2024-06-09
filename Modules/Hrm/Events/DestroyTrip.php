<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTrip
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $travel;

    public function __construct($travel)
    {
        $this->travel = $travel;
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
