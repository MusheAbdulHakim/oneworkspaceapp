<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Events\CreateSeatingTemplateDetail;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSeatingTemplateDetailLis
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
    public function handle(CreateSeatingTemplateDetail $event)
    {
        if (module_is_active('PabblyConnect')) {
            $seatingtemplatedetail = $event->seatingtemplatedetail;

            $pabbly_array = [
                'Ticket Type' => $seatingtemplatedetail->ticket_type,
                'Ticket Price' => $seatingtemplatedetail->price,
                'Ticket Max Seat' => $seatingtemplatedetail->max_seat,
            ];

            $action = 'New Seating Template Details';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
