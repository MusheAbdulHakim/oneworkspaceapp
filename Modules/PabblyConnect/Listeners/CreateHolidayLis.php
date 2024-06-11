<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Events\CreateHolidays;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHolidayLis
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
    public function handle(CreateHolidays $event)
    {
        if (module_is_active('PabblyConnect')) {
            $holiday = $event->holiday;
            $action = 'New Holidays';
            $module = 'Hrm';
            $pabbly_array = array(
                "occasion"   => $holiday['occasion'],
                "start_date" => $holiday['start_date'],
                "end_date"   => $holiday['end_date'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}