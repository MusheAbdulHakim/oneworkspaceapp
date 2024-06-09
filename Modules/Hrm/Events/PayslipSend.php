<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class PayslipSend
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $id;
    public $month;
    public $payslip;

    public function __construct($id, $month, $payslip)
    {
        $this->id = $id;
        $this->month = $month;
        $this->payslip = $payslip;
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
