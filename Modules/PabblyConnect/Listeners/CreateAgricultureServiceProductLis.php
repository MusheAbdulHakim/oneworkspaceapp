<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureServiceProduct;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureServiceProductLis
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
    public function handle(CreateAgricultureServiceProduct $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $serviceproduct = $event->serviceproduct;

            $pabbly_array = [
                'Agriculture Service Product' => $serviceproduct->name,
                'Service Product Purchase Price' => $serviceproduct->purchase_price,
                'Service Product Sale Price' => $serviceproduct->sales_price,
            ];

            $action = 'New Agriculture Service Product';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
