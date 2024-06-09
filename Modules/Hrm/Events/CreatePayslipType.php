<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreatePayslipType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $paysliptype;

    public function __construct($request, $paysliptype)
    {
        $this->request = $request;
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
