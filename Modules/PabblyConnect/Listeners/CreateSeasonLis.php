<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Events\CreateSeason;

class CreateSeasonLis
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
    public function handle(CreateSeason $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $tourseason = $event->tourseason;

            $pabbly_array = [
                'Season Name' => $tourseason->season_name
            ];

            $action = 'New Season';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
