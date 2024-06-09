<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCustomer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $customer;
    public function __construct($customer)
    {
        $this->customer = $customer;
    }
}
