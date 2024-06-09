<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAllowance
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $allowance;

    public function __construct($allowance)
    {
        $this->allowance = $allowance;
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
