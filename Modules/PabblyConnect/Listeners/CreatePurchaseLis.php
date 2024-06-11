<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use App\Models\Warehouse;
use Modules\Pos\Events\CreatePurchase;

class CreatePurchaseLis
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
    public function handle(CreatePurchase $event)
    {

        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $purchase = $event->purchase;

            if (array_column($request->items, 'item')) {
                $product =  array_column($request->items, 'item');
                $product = \Modules\ProductService\Entities\ProductService::whereIn('id', $product)->get()->pluck('name')->toArray();
                if (count($product) > 0) {
                    $product_name = implode(',', $product);
                }
                $purchase->product = $product_name;
            }
            if ($request->vender_id) {
                $vendor = User::find($request->vender_id);
                // unset($purchase->vender_name);
            } else {
                // unset($purchase->vender_id);
            }
            if ($request->warehouse_id) {
                $warehouse = Warehouse::where('id', $request->warehouse_id)->first();
                // $purchase->warehouse_id = $warehouse->name;
            }
            if ($purchase->category_id) {
                $category = \Modules\ProductService\Entities\Category::where('id', $purchase->category_id)->where('type', 2)->first();
                // $purchase->category_id = $category->name;
            }

            unset($purchase->user_id);
            $action = 'New Purchase';
            $module = 'Pos';
            $pabbly_array = array(
                "vendor_name"    => $vendor->name,
                "date"           => $request['purchase_date'],
                "category"       => $category->name,
                "warehouse"      => $warehouse->name,
                "Purchase Item"  => $request->items,
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
