<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreatePaymentMonthlyPayslip
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $employeePayslip;

    public function __construct($employeePayslip)
    {
        $this->employeePayslip = $employeePayslip;
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
