<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CarDealership\Entities\Category;
use Modules\CarDealership\Events\CreateDealershipProduct;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDealershipProductLis
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
    public function handle(CreateDealershipProduct $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $dealershipProduct = $event->dealershipProduct;

            $category = Category::find($dealershipProduct->category_id);

            $pabbly_array = [
                'Product' => $dealershipProduct->name,
                'SKU' => $dealershipProduct->sku,
                'Category' => $category->name,
                'Description' => $dealershipProduct->description,
                'Quantity' => $dealershipProduct->quantity,
                'Sale Price' => $dealershipProduct->sale_price,
                'Purchase Price' => $dealershipProduct->purchase_price,
            ];

            $action = 'New Dealership Product';
            $module = 'CarDealership';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
