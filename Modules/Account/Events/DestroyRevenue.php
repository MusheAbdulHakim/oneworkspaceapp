<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyRevenue
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $revenue;
    public function __construct($revenue)
    {
        $this->revenue = $revenue;
    }
}
