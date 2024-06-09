<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class UpdateTax
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $tax;
    public function __construct($request,$tax)
    {
        $this->request = $request;
        $this->tax = $tax;
    }
}
