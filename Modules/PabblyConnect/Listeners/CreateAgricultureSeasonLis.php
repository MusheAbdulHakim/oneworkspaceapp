<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureSeason;
use Modules\AgricultureManagement\Events\CreateAgricultureSeason;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureSeasonLis
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
    public function handle(CreateAgricultureSeason $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agricultureseason = $event->agricultureseason;

            $season_type = AgricultureSeason::find($agricultureseason->season);

            $pabbly_array = [
                'Agriculture Season Title' => $agricultureseason->name,
                'Agriculture Season Type' => $season_type->name,
                'Year' => $agricultureseason->year,
            ];

            $action = 'New Agriculture Season';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
