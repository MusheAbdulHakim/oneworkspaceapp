<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class CreatePayment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $bill_payment;
    public $payment;

    public function __construct($request ,$bill_payment,$payment)
    {
        $this->request = $request;
        $this->bill_payment = $bill_payment;
        $this->payment = $payment;
    }

}
