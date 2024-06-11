<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Events\CreateFreightPrice;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightPriceLis
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
    public function handle(CreateFreightPrice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $price = $event->price;

            $pabbly_array = [
                'Price Title' => $price->name,
                'Volume Price' => $price->volume_price,
                'Weight Price' => $price->weight_price,
                'Service Price' => $price->service_price
            ];

            $action = 'New Freight Price';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
