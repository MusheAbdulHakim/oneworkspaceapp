<?php

namespace Modules\Stripe\Events;

use Illuminate\Queue\SerializesModels;

class StripePaymentStatus
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;
    public $type;
    public $payment;

    public function __construct($data,$type,$payment)
    {
        $this->data = $data;
        $this->type = $type;
        $this->payment = $payment;
    }
}
