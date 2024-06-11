<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Entities\BodyPart;
use Modules\GymManagement\Entities\Equipment;
use Modules\GymManagement\Events\CreateExercise;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateExerciseLis
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
    public function handle(CreateExercise $event)
    {
        if (module_is_active('PabblyConnect')) {
            $exercise = $event->exercise;

            $body_part = BodyPart::find($exercise->exercise_for);
            $equipment_id = explode(',', $exercise->equipment_id);
            $equipment = Equipment::whereIn('id', $equipment_id)->get();

            $equipmentNames = collect($equipment)->pluck('name')->toArray();

            $pabbly_array = [
                'Exercise Title' => $exercise->name,
                'Exercise For' => $body_part->name,
                'Equipments' => $equipmentNames,
            ];

            $action = 'New Exercise';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
