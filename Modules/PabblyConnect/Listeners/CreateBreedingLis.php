<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DairyCattleManagement\Entities\Animal;
use Modules\DairyCattleManagement\Entities\Breeding;
use Modules\DairyCattleManagement\Events\CreateBreeding;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBreedingLis
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
    public function handle(CreateBreeding $event)
    {
        if (module_is_active('PabblyConnect')) {
            $breeding = $event->breeding;

            $animal = Animal::find($breeding->animal_id);

            $pabbly_array = [
                'Animal Name' => $animal->name,
                'Animal Breed' => $animal->breed,
                'Breeding Date' => $breeding->breeding_date,
                'Breeding Gestation' => $breeding->gestation,
                'Breeding Status' => Breeding::$breedingstatus[$breeding->breeding_status],
                'Due Date' => $breeding->due_date,
                'Notes' => $breeding->note,
            ];

            $action = 'New Breeding';
            $module = 'DairyCattleManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
