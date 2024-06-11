<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MobileServiceManagement\Events\MobileServiceRequestTrackingStatusCreate;
use Modules\PabblyConnect\Entities\PabblySend;

class MobileServiceRequestTrackingStatysCreateLis
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
    public function handle(MobileServiceRequestTrackingStatusCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $trackingStatus = $event->trackingStatus;

            $pabbly_array = [
                'Status Title' => $trackingStatus->status_name,
            ];

            $action = 'New Mobile Service Traking Status';
            $module = 'MobileServiceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
