<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DestroyVendor
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $vendor;
    public function __construct($vendor)
    {
        $this->vendor = $vendor;
    }
}
