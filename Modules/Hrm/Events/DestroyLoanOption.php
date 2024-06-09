<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLoanOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $loanoption;

    public function __construct($loanoption)
    {
        $this->loanoption = $loanoption;
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
