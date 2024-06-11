<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DairyCattleManagement\Events\CreateAnimal;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAnimalLis
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
    public function handle(CreateAnimal $event)
    {
        if (module_is_active('PabblyConnect')) {
            $animal = $event->animal;
            $request = $event->request;

            $health_status = '';
            if ($animal->health_status == 0) {
                $health_status = 'Healthy';
            } elseif ($animal->health_status == 1) {
                $health_status = 'Sick';
            } else {
                $health_status = 'Injured';
            }

            $breeding_status = '';
            if ($animal->breeding == 0) {
                $breeding_status = 'Ready for Breeding';
            } elseif ($animal->breeding == 1) {
                $breeding_status = 'Pregnant';
            } else {
                $breeding_status = 'Not Ready';
            }

            $pabbly_array = [
                'Animal Name' => $animal->name,
                'Animal Species' => $animal->species,
                'Animal Breed' => $animal->breed,
                'Animal Birth Date' => $animal->birth_date,
                'Animal Gender' => $animal->gender,
                'Animal Health Status' => $health_status,
                'Animal Weight' => $animal->weight,
                "Animal Breeding" => $breeding_status,
                "Notes" => $animal->note,
            ];

            $action = 'New Animal';
            $module = 'DairyCattleManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
