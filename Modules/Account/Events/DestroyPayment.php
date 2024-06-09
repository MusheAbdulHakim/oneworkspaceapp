<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPayment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $payment;
    public function __construct($payment)
    {
        $this->payment = $payment;
    }
}
