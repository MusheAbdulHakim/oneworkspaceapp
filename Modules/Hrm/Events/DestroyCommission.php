<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCommission
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $commission;

    public function __construct($commission)
    {
        $this->commission = $commission;
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
