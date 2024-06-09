<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateAllowanceOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $allowanceoption;

    public function __construct($request, $allowanceoption)
    {
        $this->request = $request;
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
