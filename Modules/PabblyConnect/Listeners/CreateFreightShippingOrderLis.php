<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Entities\FreightContainer;
use Modules\FreightManagementSystem\Entities\FreightPrice;
use Modules\FreightManagementSystem\Events\CreateFreightShippingOrder;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightShippingOrderLis
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
    public function handle(CreateFreightShippingOrder $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $shipping = $event->shipping;

            $container = FreightContainer::find($request->container);
            $pricing = FreightPrice::find($request->pricing);

            $pabbly_array = [
                'Customer Name' => $shipping->customer_name,
                'Customer Email' => $shipping->customer_email,
                'Direction' => $shipping->direction,
                'Transport' => $shipping->transport,
                'Loading Port' => $shipping->loading_port,
                'Discharge Port' => $shipping->discharge_port,
                'Vessel' => $shipping->vessel,
                'Date' => $shipping->date,
                'Barcode' => $shipping->barcode,
                'Tracking Number' => $shipping->tracking_no,
                'Container' => $container->name,
                'Price' => $pricing->name,
                'Sale Price' => $request->sale_price,
            ];

            $action = 'Update Freight Shipping Order';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
