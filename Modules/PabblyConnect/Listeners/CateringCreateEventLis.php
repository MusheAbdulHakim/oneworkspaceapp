<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CateringManagement\Events\CateringCreateEvent;
use Modules\PabblyConnect\Entities\PabblySend;

class CateringCreateEventLis
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
    public function handle(CateringCreateEvent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $events = $event->events;

            $pabbly_array = [
                'Event Title' => $events->name
            ];

            $action = 'New Event';
            $module = 'CateringManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
