<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class UpdateVendor
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $vendor;
    public function __construct($vendor,$request)
    {
        $this->request = $request;
        $this->vendor = $vendor;
    }
}
