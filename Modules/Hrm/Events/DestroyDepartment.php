<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDepartment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $department;

    public function __construct($department)
    {
        $this->department = $department;
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
