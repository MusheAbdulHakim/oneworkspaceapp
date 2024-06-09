<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class DestroyProduct
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $productService;
    public function __construct($productService)
    {
        $this->productService = $productService;
    }
}
