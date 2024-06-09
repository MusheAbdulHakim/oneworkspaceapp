<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyMarkAttendance
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $attendance;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
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
