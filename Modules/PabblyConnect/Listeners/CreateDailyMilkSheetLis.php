<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DairyCattleManagement\Entities\Animal;
use Modules\DairyCattleManagement\Events\CreateDailyMilkSheet;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDailyMilkSheetLis
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
    public function handle(CreateDailyMilkSheet $event)
    {
        if (module_is_active('PabblyConnect')) {
            $dailymilksheet = $event->dailymilksheet;

            $animal = Animal::find($dailymilksheet->animal_id);

            $pabbly_array = [
                'Animal Name' => $animal->name,
                'Animal Species' => $animal->species,
                'Animal Breed' => $animal->breed,
                'Birth Date' => $animal->birth_date,
                'Start Date' => $dailymilksheet->start_date,
                'End Date' => $dailymilksheet->end_date,
                'Morning Milk Capacity' => $dailymilksheet->morning_milk,
                'Evening Milk Capacity' => $dailymilksheet->evening_milk
            ];

            $action = 'New Daily Milk Sheet';
            $module = 'DairyCattleManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
