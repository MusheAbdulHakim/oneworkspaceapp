<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Events\CreateNutrition;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNutritionLis
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
    public function handle(CreateNutrition $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $nutrition = $event->nutrition;

            $pabbly_array = [
                'Name' => $nutrition->name,
                'Food' => json_decode($nutrition->food_name)
            ];

            $action = 'New Nutrition';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
