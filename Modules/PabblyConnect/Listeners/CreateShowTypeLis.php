<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Events\CreateShowType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateShowTypeLis
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
    public function handle(CreateShowType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $showtype = $event->showtype;

            $pabbly_array = [
                'Show Type' => $showtype->name
            ];

            $action = 'New Show Type';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
