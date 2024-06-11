<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Events\CreateCastType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCastTypeLis
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
    public function handle(CreateCastType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $casttype = $event->casttype;

            $pabbly_array = [
                'Cast Type' => $casttype->name,
            ];

            $action = 'New Cast Type';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
