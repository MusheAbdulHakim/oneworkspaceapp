<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDesignation
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $designation;

    public function __construct($designation)
    {
        $this->designation = $designation;
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
