<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DairyCattleManagement\Entities\Animal;
use Modules\DairyCattleManagement\Entities\DailyMilkSheet;
use Modules\DairyCattleManagement\Events\CreateMilkInventory;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMilkInventoryLis
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
    public function handle(CreateMilkInventory $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $milkinventory = $event->milkinventory;

            $idArray = explode(',', $milkinventory->daily_milksheet_id);

            $milkSheetRecords = DailyMilkSheet::whereIn('id', $idArray)->get();

            $resultArray = [];

            foreach ($milkSheetRecords as $dailyMilkSheet) {

                $animalInfo = Animal::select('name', 'breed', 'birth_date')->find($dailyMilkSheet->animal_id);

                $animalInfoArray = $animalInfo->toArray();

                $resultArray[] = [
                    'Animal Info' => $animalInfoArray,
                    'Date' => $milkinventory->date,
                    'Start Date' => $dailyMilkSheet->start_date,
                    'End Date' => $dailyMilkSheet->end_date,
                    'Morning Milk' => $dailyMilkSheet->morning_milk,
                    'Evening Milk' => $dailyMilkSheet->evening_milk,
                ];
            }
            $action = 'New Milk Inventory';
            $module = 'DairyCattleManagement';
            PabblySend::SendPabblyCall($module, $resultArray, $action);
        }
    }
}
