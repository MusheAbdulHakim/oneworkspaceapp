<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateLeaveType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $leavetype;

    public function __construct($request, $leavetype)
    {
        $this->request = $request;
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
