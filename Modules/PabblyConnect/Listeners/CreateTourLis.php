<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Entities\Season;
use Modules\TourTravelManagement\Entities\TransportType;
use Modules\TourTravelManagement\Events\CreateTour;

class CreateTourLis
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
    public function handle(CreateTour $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $tour = $event->tour;

            $transport_type = TransportType::find($tour->transport_id);
            $season = Season::find($tour->season_id);

            $pabbly_array = [
                'Tour Title' => $tour->tour_name,
                'Tour Published Date' => $tour->published_date,
                'Tour Days' => $tour->tour_days,
                'Transport Type Title' => $transport_type->transport_type_name,
                'Season Title' => $season->season_name,
                'From' => $tour->from,
                'To' => $tour->to,
                'Total Seats' => $tour->total_seat,
                'Available Seats' => $tour->available_seat,
                'Booking Start Date' => $tour->booking_start_date,
                'Booking End Date' => $tour->booking_end_date,
                'Tour Start Date' => $tour->start_date,
                'Tour End Date' => $tour->end_date,
                'Adult Price' => $tour->adult_price,
                'Child Price' => $tour->child_price
            ];

            $action = 'New Tour';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
