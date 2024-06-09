<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class CreateBill
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $bill;

    public function __construct($request ,$bill)
    {
        $this->request = $request;
        $this->bill = $bill;
    }

}
