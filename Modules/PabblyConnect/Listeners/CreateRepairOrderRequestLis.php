<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\RepairManagementSystem\Events\CreateRepairOrderRequest;

class CreateRepairOrderRequestLis
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
    public function handle(CreateRepairOrderRequest $event)
    {
        if (module_is_active('PabblyConnect')) {
            $repair_order_request = $event->repair_order_request;

            $pabbly_array = [
                'Product Name' => $repair_order_request->product_name,
                'Product Quantity' => $repair_order_request->product_quantity,
                'Customer Name' => $repair_order_request->customer_name,
                'Customer Email' => $repair_order_request->customer_email,
                'Customer Mobile Number' => $repair_order_request->customer_mobile_no,
                'Order Date' => $repair_order_request->date,
                'Expire Date' => $repair_order_request->expiry_date,
                'Location' => $repair_order_request->location
            ];

            $action = 'New Repair Order Request';
            $module = 'RepairManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
