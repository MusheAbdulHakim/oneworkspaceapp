<?php

namespace Modules\Account\Listeners;

use App\Events\DeleteProductService;
use Modules\Account\Entities\BillProduct;

class ProductServiceDelete
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DeleteProductService $event)
    {
        $bill_product=BillProduct::where('product_id',$event->id)->get();

        if(count($bill_product) != 0)
        {
            return 'false';
        }
    }
}
