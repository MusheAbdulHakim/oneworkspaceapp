<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLoan
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $loan;

    public function __construct($loan)
    {
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
