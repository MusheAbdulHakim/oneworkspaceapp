<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class CreateBankTransfer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $transfer;

    public function __construct($request ,$transfer)
    {
        $this->request = $request;
        $this->transfer = $transfer;
    }
}
