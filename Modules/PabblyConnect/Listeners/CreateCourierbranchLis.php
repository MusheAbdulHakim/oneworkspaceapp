<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CourierManagement\Events\Courierbranchcreate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourierbranchLis
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
    public function handle(Courierbranchcreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $branchData = $event->branchData;

            $pabbly_array = [
                'Branch Name' => $branchData->branch_name,
                'Branch Location' => $branchData->branch_location,
                'Branch City' => $branchData->city,
                'Branch State' => $branchData->state,
                'Branch Country' => $branchData->country
            ];

            $action = 'New Courier Branch';
            $module = 'CourierManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
