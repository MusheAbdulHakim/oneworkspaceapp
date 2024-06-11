<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Entities\FreightContainer;
use Modules\FreightManagementSystem\Events\UpdateFreightShipping;
use Modules\PabblyConnect\Entities\PabblySend;

class UpdateFreightShippingLis
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
    public function handle(UpdateFreightShipping $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $service = $event->service;

            $user = User::find($service->user_id);
            $container = FreightContainer::find($service->container);

            $pabbly_array = [
                'User Name' => $user->name,
                'User Email' => $user->email,
                'Customer Name' => $service->customer_name,
                'Customer Email' => $service->customer_email,
                'Direction' => $service->direction,
                'Transport' => $service->transport,
                'Loading Port' => $service->loading_port,
                'Discharge Port' => $service->discharge_port,
                'Vessel' => $service->vessel,
                'Date' => $service->date,
                'Barcode' => $service->barcode,
                'Tracking Number' => $service->tracking_no,
                'Container' => $container->name,
                'Quantity' => $service->quantity,
                'Volume' => $service->volume
            ];

            $action = 'Update Freight Shipping Container';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
