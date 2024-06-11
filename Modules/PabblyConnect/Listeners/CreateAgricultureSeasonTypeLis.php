<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureSeasonType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureSeasonTypeLis
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
    public function handle(CreateAgricultureSeasonType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agricultureseasontype = $event->agricultureseasontype;

            $pabbly_array = [
                'Agriculture Season Type Title' => $agricultureseasontype->name,
            ];

            $action = 'New Agriculture Season Type';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
