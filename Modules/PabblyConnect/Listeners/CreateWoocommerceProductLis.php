<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\ProductService;
use Modules\WordpressWoocommerce\Events\CreateWoocommerceProduct;

class CreateWoocommerceProductLis
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
    public function handle(CreateWoocommerceProduct $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $product = $event->Product;

            $products = ProductService::find($product->original_id);

            $pabbly_array = [
                'Product Title' => $products->name,
                'SKU' => $products->sku,
                'Sale Price' => $products->sale_price,
                'Purchase Price' => $products->purchase_price,
                'Product Category' => $product->type,
            ];

            $action = 'New Product';
            $module = 'WordpressWoocommerce';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
