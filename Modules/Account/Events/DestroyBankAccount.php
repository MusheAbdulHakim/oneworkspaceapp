<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBankAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $bankAccount;

    public function __construct($bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }
}
