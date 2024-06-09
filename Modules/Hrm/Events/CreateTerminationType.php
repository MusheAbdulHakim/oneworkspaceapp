<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateTerminationType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $terminationtype;

    public function __construct($request, $terminationtype)
    {
        $this->request = $request;
        $this->terminationtype = $terminationtype;
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
