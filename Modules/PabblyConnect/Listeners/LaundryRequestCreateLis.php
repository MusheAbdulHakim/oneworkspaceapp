<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LaundryManagement\Entities\LaundryLocation;
use Modules\LaundryManagement\Entities\LaundryService;
use Modules\LaundryManagement\Events\LaundryRequestCreate;
use Modules\PabblyConnect\Entities\PabblySend;

class LaundryRequestCreateLis
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
    public function handle(LaundryRequestCreate $event)
    {
        if (module_is_active('Zapier')) {
            $request = $event->request;
            $laundryrequest = $event->laundryrequest;

            $services_id = explode(',', $laundryrequest->services);
            $services = LaundryService::whereIn('id', $services_id)->get();
            $location = LaundryLocation::find($laundryrequest->location);

            $allData = [];

            foreach ($services as $service) {
                $data = [
                    'Service Title' => $service->name,
                    'Service Cost' => $service->cost,
                ];
                $allData[] = $data;
            }

            $pabbly_array = [
                'Customer Name' => $laundryrequest->name,
                'Phone' => $laundryrequest->phone,
                'Email' => $laundryrequest->email,
                'Address' => $laundryrequest->address,
                'Services' => $allData,
                'Location' => $location->name,
                'Number of Cloths' => $laundryrequest->cloth_no,
                'Total' => $laundryrequest->total,
                'Pickup Date' => $laundryrequest->pickup_date,
                'Delivery Date' => $laundryrequest->delivery_date,
                'Instructions' => $laundryrequest->instructions,
            ];

            $action = 'New Laundry Request';
            $module = 'LaundryManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
