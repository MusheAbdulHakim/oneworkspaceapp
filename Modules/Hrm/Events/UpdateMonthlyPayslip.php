<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class UpdateMonthlyPayslip
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $payslipEmployee;

    public function __construct($payslipEmployee, $request)
    {
        $this->request = $request;
        $this->payslipEmployee = $payslipEmployee;
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
