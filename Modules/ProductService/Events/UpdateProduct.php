<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class UpdateProduct
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $productService;
    public function __construct($request,$productService)
    {
        $this->request = $request;
        $this->productService = $productService;
    }
}
