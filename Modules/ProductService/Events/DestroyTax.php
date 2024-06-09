<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTax
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tax;
    public function __construct($tax)
    {
        $this->tax = $tax;
    }
}
