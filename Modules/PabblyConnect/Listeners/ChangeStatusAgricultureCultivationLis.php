<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureCycles;
use Modules\AgricultureManagement\Entities\AgricultureDepartment;
use Modules\AgricultureManagement\Entities\AgricultureOffices;
use Modules\AgricultureManagement\Entities\AgricultureUser;
use Modules\AgricultureManagement\Events\ChangeStatusAgricultureCultivation;
use Modules\PabblyConnect\Entities\PabblySend;

class ChangeStatusAgricultureCultivationLis
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
    public function handle(ChangeStatusAgricultureCultivation $event)
    {
        if (module_is_active('PabblyConnect')) {
            $status = $event->status;
            $agriculturecultivation = $event->agriculturecultivation;

            $farmer = AgricultureUser::find($agriculturecultivation->farmer);
            $cycle = AgricultureCycles::find($agriculturecultivation->agriculture_cycle);
            $department = AgricultureDepartment::find($agriculturecultivation->department);
            $office = AgricultureOffices::find($agriculturecultivation->office);

            $pabbly_array = [
                'Cultivation Name' => $agriculturecultivation->name,
                'Farmer Name' => $farmer->name,
                'Agriculture Cycle' => $cycle->name,
                'Agriculture Department' => $department->name,
                'Agriculture Office' => $office->name,
                'Status' => $status
            ];

            $action = 'Update Agriculture Cultivation Status';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
