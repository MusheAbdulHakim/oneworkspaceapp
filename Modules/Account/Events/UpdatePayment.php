<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePayment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $payment;
    public function __construct($payment,$request)
    {
        $this->request = $request;
        $this->payment = $payment;
        // dd($this->payment,$this->request);
    }
}
