<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Entities\Tour;
use Modules\TourTravelManagement\Entities\TourInquiry;
use Modules\TourTravelManagement\Events\CreatePersonDetail;

class CreatePersonDetailLis
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
    public function handle(CreatePersonDetail $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $personal_information = $event->person_information;

            $tour_data = Tour::find($personal_information->tour_id);
            $inquiry_data = TourInquiry::find($personal_information->inquiry_id);

            $pabbly_array = [
                'Tour Title' => $tour_data->tour_name,
                'Tour Inquiry Person' => $inquiry_data->person_name,
                'Person Email' => $inquiry_data->email_id,
                'Personal Details' => $request->person_details
            ];

            $action = 'New Person Detail';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
