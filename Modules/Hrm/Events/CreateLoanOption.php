<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateLoanOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $loanoption;

    public function __construct($request, $loanoption)
    {
        $this->request = $request;
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
