<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureActivities;
use Modules\AgricultureManagement\Entities\AgricultureCycles;
use Modules\AgricultureManagement\Entities\AgricultureDepartment;
use Modules\AgricultureManagement\Entities\AgricultureOffices;
use Modules\AgricultureManagement\Entities\AgricultureUser;
use Modules\AgricultureManagement\Events\AssignActivityCultivation;
use Modules\PabblyConnect\Entities\PabblySend;

class AssignActivityCultivationLis
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
    public function handle(AssignActivityCultivation $event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $agriculturecultivation = $event->agriculturecultivation;

            $farmer = AgricultureUser::find($agriculturecultivation->farmer);
            $cycle = AgricultureCycles::find($agriculturecultivation->agriculture_cycle);
            $department = AgricultureDepartment::find($agriculturecultivation->department);
            $office = AgricultureOffices::find($agriculturecultivation->office);
            $activitesArray = json_decode($agriculturecultivation->activites);
            $activity_data = AgricultureActivities::whereIn('id', $activitesArray)->get();

            $activityArray = [];

            foreach ($activity_data as $activity) {
                $attributes = [
                    'Activity Name' => $activity->name,
                    'Agriculture Date' => $activity->agriculture_date,
                    'Harvest Date' => $activity->harvest_date,
                    'Agriculture Season' => $activity->agri_season,
                ];
                $activityArray[] = $attributes;
            }

            $pabbly_array = [
                'Agriculture Cultivation Title' => $agriculturecultivation->name,
                'Farmer' => $farmer->name,
                'Agriculture Cycle' => $cycle->name,
                'Agriculture Department' => $department->name,
                'Agriculture Office' => $office->name,
                'Agriculture Activites' => $activityArray,
            ];

            $action = 'Assign Cultivation Activity';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
