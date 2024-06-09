<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateDesignation
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $designation;

    public function __construct($request, $designation)
    {
        $this->request = $request;
        $this->designation = $designation;
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
