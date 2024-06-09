<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class UpdateBill
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $bill;
    public function __construct($bill,$request)
    {
        $this->request = $request;
        $this->bill = $bill;
    }
}
