<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateRoomFeature;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateRoomFeatureLis
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
    public function handle(CreateRoomFeature $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $feature = $event->feature;

            $pabbly_array = [
                'Title' => $feature->name,
                'Icon' => $feature->icon
            ];

            $action = 'New Features';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
