<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class CreateRevenue
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $revenue;

    public function __construct($request ,$revenue)
    {
        $this->request = $request;
        $this->revenue = $revenue;
    }

}
