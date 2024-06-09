<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class CreateProduct
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $productService;
    public function __construct($productService,$request)
    {
        $this->request = $request;
        $this->productService = $productService;
    }
}
