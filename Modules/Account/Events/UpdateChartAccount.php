<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class UpdateChartAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $chartOfAccount;
    public function __construct($chartOfAccount,$request)
    {
        $this->request = $request;
        $this->chartOfAccount = $chartOfAccount;
    }
}
