<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LaundryManagement\Events\LaundryLocationCreate;
use Modules\PabblyConnect\Entities\PabblySend;

class LaundryLocationCreateLis
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
    public function handle(LaundryLocationCreate $event)
    {
        if (module_is_active('Webhook')) {
            $location = $event->location;

            $pabbly_array = [
                'Location' => $location->name,
                'Cost' => $location->cost,
            ];

            $action = 'New Laundry Location';
            $module = 'LaundryManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
