<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\ProductService;
use Modules\SalesAgent\Entities\Program;
use Modules\SalesAgent\Events\SalesAgentOrderCreate;

class SalesAgentOrderCreateLis
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
    public function handle(SalesAgentOrderCreate $event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $order = $event->order;
            $orderItems = $event->OrderItems;

            $uniqueCombinations = [];

            foreach ($orderItems as $orderItem) {
                $programId = $orderItem->program_id;
                $itemId = $orderItem->item_id;

                if (!isset($uniqueCombinations[$programId][$itemId])) {
                    $uniqueCombinations[$programId][$itemId] = [
                        'quantity' => $orderItem->quantity,
                        'price' => $orderItem->price,
                        'description' => $orderItem->description,
                    ];
                }
            }

            $resultArray = [];

            foreach ($uniqueCombinations as $programId => $items) {
                foreach ($items as $itemId => $itemData) {
                    $resultArray[] = [
                        'program_id' => $programId,
                        'item_id' => $itemId,
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price'],
                        'description' => $itemData['description'],
                    ];
                }
            }

            $programIds = [];
            $itemIds = [];

            foreach ($resultArray as $item) {
                $programId = $item['program_id'];
                $itemId = $item['item_id'];

                $programIds[] = $programId;
                $itemIds[] = $itemId;
            }

            $programIds = array_unique($programIds);

            $programs = Program::whereIn('id', $programIds)->get();
            $item_data = ProductService::whereIn('id', $itemIds)->get();

            $attributesArray = [];

            foreach ($item_data as $item) {
                $attributes = [
                    'Name' => $item->name,
                    'SKU' => $item->sku,
                    'Sale Price' => $item->sale_price,
                    'Purchase Price' => $item->purchase_price,
                    'Quantity' => $item->quantity,
                ];
                $attributesArray[] = $attributes;
            }

            $programArray = [];

            foreach ($programs as $program) {
                $program_attributes = [
                    'Name' => $program->name,
                    'From Date' => $program->from_date,
                    'To Date' => $program->to_date,
                    'Description' => $program->description,
                ];
                $programArray[] = $program_attributes;
            }

            $agent = User::find($order->user_id);

            $pabbly_array = [
                'Agent Name' => $agent->name,
                'Order Date' => $order->order_date,
                'Delivery Date' => $order->delivery_date,
                "Sales Agent Program" => $programArray,
                "Item Details" => $attributesArray,
                'Total Amount' => $request->totalAmount,
            ];

            $action = 'New Sales Agent Order';
            $module = 'SalesAgent';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
