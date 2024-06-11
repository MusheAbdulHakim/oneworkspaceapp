<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\ProductService;
use Modules\SalesAgent\Events\SalesAgentProgramCreate;

class CreateSalesAgentProgramLis
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
    public function handle(SalesAgentProgramCreate $event)
    {
        if (module_is_active('Zapier')) {
            $request = $event->request;
            $program = $event->program;
            $ProgramItems = $event->ProgramItems;

            foreach ($ProgramItems as $programItem) {
                $data = [
                    'product_type' => $programItem->product_type,
                    'from_amount' => $programItem->from_amount,
                    'to_amount' => $programItem->to_amount,
                    'discount' => $programItem->discount,
                    'items' => $programItem->items,
                ];

                $allData[] = $data;
            }

            $itemIds = [];

            foreach ($allData as $data) {
                $itemIds = array_merge($itemIds, explode(',', $data['items']));
            }

            $itemIds = array_unique($itemIds);

            $items = ProductService::whereIn('id', $itemIds)->get();

            $attributesArray = [];

            foreach ($items as $item) {
                $attributes = [
                    'Name' => $item->name,
                    'SKU' => $item->sku,
                    'Sale Price' => $item->sale_price,
                    'Purchase Price' => $item->purchase_price,
                    'Quantity' => $item->quantity,
                ];
                $attributesArray[] = $attributes;
            }

            $pabbly_array = [
                'Name' => $program['name'],
                "From Date" => $program['from_date'],
                "To Date" => $program['to_date'],
                "Description" => $program['description'],
                "Items" => $allData,
                "Item Details" => $attributesArray
            ];

            $action = 'New Sales Agent Program';
            $module = 'SalesAgent';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
