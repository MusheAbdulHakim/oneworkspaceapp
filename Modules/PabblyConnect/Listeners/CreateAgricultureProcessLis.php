<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureProcess;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureProcessLis
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
    public function handle(CreateAgricultureProcess $event)
    {
        if (module_is_active('PabblyConnect')) {
            $agricultureprocess = $event->agricultureprocess;
            $pabbly_array = [
                "Process Title" => $agricultureprocess->name,
                "Process Hours" => $agricultureprocess->hours,
                "Process Description" => $agricultureprocess->description,
            ];

            $action = 'New Agriculture Process';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
