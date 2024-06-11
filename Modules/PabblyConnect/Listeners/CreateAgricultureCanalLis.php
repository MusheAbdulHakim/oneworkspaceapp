<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureOffices;
use Modules\AgricultureManagement\Events\CreateAgricultureCanal;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureCanalLis
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
    public function handle(CreateAgricultureCanal $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agriculturecanal = $event->agriculturecanal;

            $office = AgricultureOffices::find($agriculturecanal->office);

            $pabbly_array = [
                'Agriculture Canal Title' => $agriculturecanal->name,
                'Agriculture Canal Code' => $agriculturecanal->code,
                'Agriculture Office' => $office->name
            ];

            $action = 'New Agriculture Canal';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
