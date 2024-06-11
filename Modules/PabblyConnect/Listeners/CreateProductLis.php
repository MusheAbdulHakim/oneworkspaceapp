<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;
use Modules\ProductService\Entities\Tax;
use Modules\ProductService\Entities\Unit;
use Modules\ProductService\Events\CreateProduct;

class CreateProductLis
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
            $product = $event->productService;
            $request = $event->request;
            $tax = [];
            if (!empty($product->tax)) {
                $tax = Tax::whereIN('id', $product->tax_id)->get()->pluck('name')->toArray();
            }
            $unit = Unit::find($product->unit_id);
            $category = Category::find($product->category_id);

            $action = 'New Product';
            $module = 'ProductService';
            $pabbly_array = array(
                "Product Name"   => $product['name'],
                "SKU"            => $product['sku'],
                "Description"    => $product['description'],
                "Sale Price"     => $product['sale_price'],
                "Purchase Price" => $product['purchase_price'],
                "Tax"            => implode(',', $tax),
                "Units In"       => $unit->name,
                "Quantity"       => $product['quantity'],
                "Category"       => $category->name,
                "Product Type"   => $product['type'],
            );
            $status = PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}