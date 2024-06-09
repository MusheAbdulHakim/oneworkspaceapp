<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLeaveType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $leavetype;

    public function __construct($leavetype)
    {
        $this->leavetype = $leavetype;
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
