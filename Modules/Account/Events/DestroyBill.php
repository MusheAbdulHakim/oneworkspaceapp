<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBill
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $bill;
    public function __construct($bill)
    {
        $this->bill = $bill;
    }
}
