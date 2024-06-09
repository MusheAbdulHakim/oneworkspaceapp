<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPayslipType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $paysliptype;

    public function __construct($paysliptype)
    {
        $this->paysliptype = $paysliptype;
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
