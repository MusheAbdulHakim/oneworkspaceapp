<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Entities\Tour;
use Modules\TourTravelManagement\Events\CreateTourInquiry;

class CreateTourInquiryLis
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
    public function handle(CreateTourInquiry $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $tour = $event->tour;

            $tour_data = Tour::find($tour->tour_id);

            $pabbly_array = [
                'Tour Title' => $tour_data->tour_name,
                'Person Name' => $tour->person_name,
                'Person Email' => $tour->email_id,
                'Person Mobile Number' => $tour->mobile_no,
                'Person Address' => $tour->address,
                'Tour Inquiry Date' => $tour->inquiry_date,
                'Tour Start Date' => $tour->tour_start_date,
                'Tour End Date' => $tour->tour_end_date,
                'Total Number of Person' => $tour->no_of_person,
                'Total Number of Adults' => $tour->no_of_adult,
                'Total Number of Child' => $tour->no_of_child,
                'Minimum Budget' => $tour->budget_minimum,
                'Maximum Budget' => $tour->budget_maximum,
                'Tour Destination' => $tour->tour_destination,
                'Tour Country' => $tour->country,
                'Total Number of Days' => $tour->no_of_days,
                'Total Number of Nights' => $tour->no_of_nights,
                'Tour Payment Status' => $tour->payment_status
            ];

            $action = 'New Tour Inquiry';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
