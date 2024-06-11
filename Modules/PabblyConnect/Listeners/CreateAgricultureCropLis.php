<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureCycles;
use Modules\AgricultureManagement\Entities\AgricultureEquipment;
use Modules\AgricultureManagement\Entities\AgricultureFleet;
use Modules\AgricultureManagement\Entities\AgricultureProcess;
use Modules\AgricultureManagement\Entities\AgricultureSeason;
use Modules\AgricultureManagement\Events\CreateAgricultureCrop;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureCropLis
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
    public function handle(CreateAgricultureCrop $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agriculturecrop = $event->agriculturecrop;

            $season = AgricultureSeason::find($agriculturecrop->season);
            $cycle = AgricultureCycles::find($agriculturecrop->cycles);

            $fleetArray = json_decode($agriculturecrop->fleet);
            $equipmentArray = json_decode($agriculturecrop->equipment);
            $processArray = json_decode($agriculturecrop->process);

            $fleets = AgricultureFleet::whereIn('id', $fleetArray)->get();
            $equipments = AgricultureEquipment::whereIn('id', $equipmentArray)->get();
            $process = AgricultureProcess::whereIn('id', $processArray)->get();

            $fleetsArray = [];
            $equipmentsArray = [];
            $process_Array = [];

            foreach ($fleets as $fleet) {
                $attributes = [
                    'Name' => $fleet->name,
                    'Capacity' => $fleet->capacity,
                    'Price' => $fleet->price,
                    'Quantity' => $fleet->quantity
                ];
                $fleetsArray[] = $attributes;
            }


            foreach ($equipments as $equipment) {
                $attributes = [
                    'Name' => $equipment->name,
                    'Price' => $equipment->price,
                    'Quantity' => $equipment->quantity
                ];
                $equipmentsArray[] = $attributes;
            }

            foreach ($process as $proces) {
                $attributes = [
                    'Name' => $proces->name,
                    'Hours' => $proces->hours,
                    'Description' => $proces->description
                ];
                $process_Array[] = $attributes;
            }

            $pabbly_array = [
                'Agriculture Corp Title' => $agriculturecrop->name,
                'Agriculture Date' => $agriculturecrop->agriculture_date,
                'Harvest Date' => $agriculturecrop->harvest_date,
                'Fleets' => $fleetsArray,
                'Equipments' => $equipmentsArray,
                'Agriculture Process' => $process_Array,
            ];

            $action = 'New Agriculture Crop';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
