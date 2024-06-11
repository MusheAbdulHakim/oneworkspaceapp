<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DairyCattleManagement\Entities\Animal;
use Modules\DairyCattleManagement\Events\CreateWeight;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateWeightLis
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
    public function handle(CreateWeight $event)
    {
        if (module_is_active('PabblyConnect')) {
            $weight = $event->weight;

            $animal = Animal::find($weight->animal_id);

            $pabbly_array = [
                'Animal Name' => $animal->name,
                'Animal Species' => $animal->species,
                'Animal Breed' => $animal->breed,
                'Birth Date' => $animal->birth_date,
                'Date' => $weight->date,
                'Age' => $weight->age,
                'Weight' => $weight->weight
            ];

            $action = 'New Weight';
            $module = 'DairyCattleManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
