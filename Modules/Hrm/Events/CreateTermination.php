<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateTermination
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $termination;

    public function __construct($request, $termination)
    {
        $this->request = $request;
        $this->termination = $termination;
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
