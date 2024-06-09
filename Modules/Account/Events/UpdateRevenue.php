<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class UpdateRevenue
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $revenue;
    public function __construct($revenue,$request)
    {
        $this->request = $request;
        $this->revenue = $revenue;
    }
}
