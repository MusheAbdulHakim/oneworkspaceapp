<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PropertyManagement\Events\CreateProperty;

class CreatePropertyLis
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
    public function handle(CreateProperty $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $property = $event->property;

            $pabbly_array = [
                'Property Title' => $property->name,
                'Property Address' => $property->address,
                'Property Country' => $property->country,
                'Property State' => $property->state,
                'Property City' => $property->city,
                'Property Pincode' => $property->pincode,
                'Property Description' => $property->description,
                'Property Security Deposite' => $property->security_deposit,
                'Property Maintenance Charge' => $property->maintenance_charge,
            ];

            $action = 'New Property';
            $module = 'PropertyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
