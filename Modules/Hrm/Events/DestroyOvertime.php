<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyOvertime
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $overtime;

    public function __construct($overtime)
    {
        $this->overtime = $overtime;
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
