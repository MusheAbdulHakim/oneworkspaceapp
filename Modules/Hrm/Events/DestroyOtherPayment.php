<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyOtherPayment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $otherpayment;

    public function __construct($otherpayment)
    {
        $this->otherpayment = $otherpayment;
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
