<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Entities\FreightService;
use Modules\FreightManagementSystem\Events\CreateFreightShippingRoute;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightShippingRouteLis
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
    public function handle(CreateFreightShippingRoute $event)
    {
        if (module_is_active('Zapier')) {
            $request = $event->request;
            $route = $event->route;

            $items = $request->items;

            $vendorIds = array_unique(array_column($items, 'vendor'));
            $serviceIds = array_unique(array_column($items, 'service'));

            $vendorNames = User::whereIn('id', $vendorIds)->pluck('name', 'id')->toArray();
            $serviceNames = FreightService::whereIn('id', $serviceIds)->pluck('name', 'id')->toArray();

            $processedItems = array_map(function ($item) use ($vendorNames, $serviceNames) {
                return [
                    'vendor' => $vendorNames[$item['vendor']] ?? null,
                    'service' => $serviceNames[$item['service']] ?? null,
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'sale_price' => $item['sale_price'],
                    'total_cost_price' => $item['total_cost_price'],
                    'total_sale_price' => $item['total_sale_price'],
                ];
            }, $items);

            $pabbly_array = [
                'Route Operation' => $route->route_operation,
                'Transport' => $route->transport,
                'Source Location' => $route->source_location,
                'Destination Location' => $route->destination_location,
                'Send Date' => $route->send_date,
                'Received Date' => $route->received_date,
                'Cost Price' => $route->cost_price,
                'Sale Price' => $route->sale_price,
                'Items' => $processedItems
            ];

            $action = 'New Freight Route';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
