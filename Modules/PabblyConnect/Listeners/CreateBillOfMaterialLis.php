<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeverageManagement\Events\CreateBillOfMaterial;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBillOfMaterialLis
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
    public function handle(CreateBillOfMaterial $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $bill_of_material = $event->bill_of_material;

            $rawItems = json_decode($request['raw_item_array'], true);

            $pabbly_array = [
                'Product Name' => $bill_of_material->product_name,
                'Unit' => $request->unit,
                'Quantity' => $request->quantity,
                'Raw Material' => $rawItems,
            ];

            $action = 'New Bill of Material';
            $module = 'BeverageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
