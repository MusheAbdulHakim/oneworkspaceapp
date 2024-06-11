<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ConsignmentManagement\Entities\Consignment;
use Modules\ConsignmentManagement\Events\CreatePurchaseOrder;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePurchaseOrderLis
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
    public function handle(CreatePurchaseOrder $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $purchaseOrder = $event->purchaseOrder;

            $consignment = Consignment::find($purchaseOrder->consignment_id);
            $vendor = User::find($purchaseOrder->vendor_id);

            $pabbly_array = [
                'Consignment Title' => $consignment->title,
                'Vendor Name' => $vendor->name,
                'Vendor Email' => $vendor->email,
                'Vendor Mobile Number' => $vendor->mobile_no,
                'Date' => $purchaseOrder->date,
                'Sub Total' => $purchaseOrder->subtotal,
                'Commission' => $purchaseOrder->commission,
                'Total Amount' => $purchaseOrder->totalamount,
            ];

            $action = 'New Purchase Order';
            $module = 'ConsignmentManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
