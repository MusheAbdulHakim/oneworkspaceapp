<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class UpdateBankAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $bankAccount;

    public function __construct($request ,$bankAccount)
    {
        $this->request = $request;
        $this->bankAccount = $bankAccount;
    }
}
