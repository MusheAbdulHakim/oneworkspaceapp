<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ConsignmentManagement\Events\CreateProduct;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateConsignmentProduct
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
    public function handle(CreateProduct $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $product = $event->product;

            $pabbly_array = [
                'Product Title' => $product->name,
                'Product Weight' => $product->weight,
                'Product Unit Price' => $product->unit_price,
                'Product Quantity' => $product->quantity,
                'Product Description' => $product->description
            ];

            $action = 'New Product';
            $module = 'ConsignmentManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
