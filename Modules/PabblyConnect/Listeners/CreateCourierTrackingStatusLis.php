<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CourierManagement\Events\Couriertrackingstatuscreate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourierTrackingStatusLis
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
    public function handle(Couriertrackingstatuscreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $trackingStatus = $event->trackingStatus;

            $pabbly_array = [
                'Status title' => $trackingStatus->status_name,
                'Status Color' => $trackingStatus->status_color,
                'Status Icon' => $trackingStatus->icon_name
            ];

            $action = 'New Courier Tracking Status';
            $module = 'CourierManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
