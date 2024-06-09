<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAllowanceTax
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $allowancetax;

    public function __construct($allowancetax)
    {
        $this->allowancetax = $allowancetax;
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
