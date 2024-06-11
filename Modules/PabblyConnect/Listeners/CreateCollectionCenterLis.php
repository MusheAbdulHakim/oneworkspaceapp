<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeverageManagement\Events\CreateCollectionCenter;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCollectionCenterLis
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
    public function handle(CreateCollectionCenter $event)
    {
        if (module_is_active('PabblyConnect')) {
            $collection_center = $event->collection_center;

            $pabbly_array = [
                'Location' => $collection_center->location_name,
                'Status' => $collection_center->status == 1 ? 'Active' : 'Inactive',
            ];

            $action = 'New Collection Center';
            $module = 'BeverageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
