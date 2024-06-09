<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBankTransfer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $transfer;

    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }
}
