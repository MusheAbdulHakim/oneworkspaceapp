<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class CreateBankAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $account;

    public function __construct($request ,$account)
    {
        $this->request = $request;
        $this->account = $account;
    }
}
