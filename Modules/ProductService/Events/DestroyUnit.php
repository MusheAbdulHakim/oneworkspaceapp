<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class DestroyUnit
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $unit;
    public function __construct($unit)
    {
        $this->unit = $unit;
    }
}
