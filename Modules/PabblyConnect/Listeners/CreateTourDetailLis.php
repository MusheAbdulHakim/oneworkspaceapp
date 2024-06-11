<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Entities\Tour;
use Modules\TourTravelManagement\Events\CreateTourDetail;

class CreateTourDetailLis
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
    public function handle(CreateTourDetail $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $tour = $event->tour;

            $tour_data = Tour::find($tour->tour_id);

            $pabbly_array = [
                'Tour Title' => $tour_data->tour_name,
                'Tour Details' => $request->tour_details,
            ];

            $action = 'New Tour Detail';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
