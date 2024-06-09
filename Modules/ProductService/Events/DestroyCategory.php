<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $category;
    public function __construct($category)
    {
        $this->category = $category;
    }
}
