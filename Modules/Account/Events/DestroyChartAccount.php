<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyChartAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $chartOfAccount;
    public function __construct($chartOfAccount)
    {
        $this->chartOfAccount = $chartOfAccount;
    }
}
