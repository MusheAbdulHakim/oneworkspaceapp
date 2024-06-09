<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyEmployee
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
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
