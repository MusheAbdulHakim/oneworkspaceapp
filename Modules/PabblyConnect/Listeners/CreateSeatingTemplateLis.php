<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Events\CreateSeatingTemplate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSeatingTemplateLis
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
    public function handle(CreateSeatingTemplate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $seatingtemplate = $event->seatingtemplate;

            $pabbly_array = [
                'Seating Layout Title' => $seatingtemplate->name,
            ];

            $action = 'New Seating Template';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
