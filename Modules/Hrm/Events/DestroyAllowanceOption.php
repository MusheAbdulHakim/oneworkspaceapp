<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAllowanceOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $allowanceoption;

    public function __construct($allowanceoption)
    {
        $this->allowanceoption = $allowanceoption;
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
