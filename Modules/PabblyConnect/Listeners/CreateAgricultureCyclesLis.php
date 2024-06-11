<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureCycles;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureCyclesLis
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
    public function handle(CreateAgricultureCycles $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agriculturecycles = $event->agriculturecycles;

            $pabbly_array = [
                'Agriculture Cycle Title' => $agriculturecycles->name
            ];

            $action = 'New Agriculture Cycle';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
