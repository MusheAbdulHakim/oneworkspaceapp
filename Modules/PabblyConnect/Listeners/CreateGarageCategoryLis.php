<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Events\CreateGarageCategory;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateGarageCategoryLis
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
    public function handle(CreateGarageCategory $event)
    {
        if (module_is_active('PabblyConnect')) {
            $category = $event->category;

            $pabbly_array = [
                'Garage Category' => $category->name
            ];

            $action = 'New Garage Category';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
