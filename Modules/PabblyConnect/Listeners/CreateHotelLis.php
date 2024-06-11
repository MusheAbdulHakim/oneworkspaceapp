<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateHotel;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHotelLis
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
    public function handle($event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $hotel = $event->hotel;

            $pabbly_array = [
                'Hotel Title' => $hotel->name,
                'Hotel Email' => $hotel->email,
                'Hotel Phone' => $hotel->phone,
                'Hotel Rating' => $hotel->ratting,
                'Hotel CheckIn Time' => $hotel->check_in,
                'Hotel CheckOut Time' => $hotel->check_out,
                'Hotel Short Description' => $hotel->short_description,
                'City' => $hotel->city,
                'State' => $hotel->state,
                'Address' => $hotel->address,
                'Zip Code' => $hotel->zip_code,
                'Description' => $hotel->description,
                'Hotel Policy' => $hotel->policy
            ];

            $action = 'New Hotel';
            $module = 'Holidayz';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
