<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateLoan
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $loan;

    public function __construct($request, $loan)
    {
        $this->request = $request;
        $this->loan = $loan;
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
