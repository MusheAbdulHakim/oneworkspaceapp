<?php

namespace Modules\ProductService\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCategory
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $category;
    public function __construct($request,$category)
    {
        $this->request = $request;
        $this->category = $category;
    }

}
