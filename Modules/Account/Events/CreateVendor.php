<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class CreateVendor
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $vendor;

    public function __construct($request ,$vendor)
    {
        $this->request = $request;
        $this->vendor = $vendor;
    }


}
