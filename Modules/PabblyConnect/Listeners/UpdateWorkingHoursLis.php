<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeautySpaManagement\Events\UpdateWorkingHours;
use Modules\PabblyConnect\Entities\PabblySend;

class UpdateWorkingHoursLis
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
    public function handle(UpdateWorkingHours $event)
    {
        if (module_is_active('PabblyConnect')) {
            $beautyworking = $event->beautyworking;

            $pabbly_array = [
                'Opening Time' => $beautyworking['opening_time'],
                'Closing Time' => $beautyworking['closing_time'],
                'Day of Weeks' => explode(',', $beautyworking['day_of_week']),
                'Holiday Settings' => $beautyworking['holiday_setting'],
            ];

            $action = 'Update Working Hours';
            $module = 'BeautySpaManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
