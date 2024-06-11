<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Events\CreateWorkoutPlan;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateWorkoutPlanLis
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
    public function handle(CreateWorkoutPlan $event)
    {
        if (module_is_active('PabblyConnect')) {
            $workoutplan = $event->workoutplan;

            $pabbly_array = [
                'Workout Plan Title' => $workoutplan->name,
                'Workout Plan Days' => $workoutplan->days
            ];

            $action = 'New Workout Plan';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
