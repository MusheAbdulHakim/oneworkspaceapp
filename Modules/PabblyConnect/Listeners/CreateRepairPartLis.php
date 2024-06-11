<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\ProductService;
use Modules\RepairManagementSystem\Entities\RepairOrderRequest;
use Modules\RepairManagementSystem\Events\CreateRepairPart;

class CreateRepairPartLis
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
    public function handle(CreateRepairPart $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $repair_order_request = $event->repair_order_request;

            $repair_order = RepairOrderRequest::find($repair_order_request->repair_id);

            $itemIds = array_unique(array_column($request['items'], 'item'));
            $itemNames = ProductService::whereIn('id', $itemIds)->pluck('name', 'id')->toArray();


            $filteredParts = array_map(function ($item) use ($itemNames) {
                return [
                    'item' => $itemNames[$item['item']] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'itemTaxPrice' => $item['itemTaxPrice'],
                    'itemTaxRate' => $item['itemTaxRate'],
                ];
            }, $request['items']);

            $pabbly_array = [
                'Product Name' => $repair_order->product_name,
                'Product Quantity' => $repair_order->product_quantity,
                'Customer Name' => $repair_order->customer_name,
                'Customer Email' => $repair_order->customer_email,
                'Customer Mobile Number' => $repair_order->customer_mobile_no,
                'Date' => $repair_order->date,
                'Exipry Date' => $repair_order->expiry_date,
                'Location' => $repair_order->location,
                'Parts' => $filteredParts,
            ];

            $action = 'New Repair Part';
            $module = 'RepairManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
