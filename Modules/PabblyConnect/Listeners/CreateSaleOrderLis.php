<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ConsignmentManagement\Entities\Consignment;
use Modules\ConsignmentManagement\Events\CreateSaleOrder;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSaleOrderLis
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
    public function handle(CreateSaleOrder $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $saleOrder = $event->saleOrder;

            $consignment = Consignment::find($saleOrder->consignment_id);
            $customer = User::find($saleOrder->customer_id);

            $pabbly_array = [
                'Consignment Title' => $consignment->title,
                'Customer Name' => $customer->name,
                'Customer Email' => $customer->email,
                'Customer Mobile Number' => $customer->mobile_no,
                'Sales Order Date' => $saleOrder->date,
                'Sub Total' => $saleOrder->subtotal,
                'Commission' => $saleOrder->commission,
                'Total Amount' => $saleOrder->totalamount,
            ];
            $action = 'New Sale Order';
            $module = 'ConsignmentManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
