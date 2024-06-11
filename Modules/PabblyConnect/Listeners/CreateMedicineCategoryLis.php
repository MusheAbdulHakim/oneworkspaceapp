<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Events\CreateMedicineCategory;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMedicineCategoryLis
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
    public function handle(CreateMedicineCategory $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $medicinecategory = $event->medicinecategory;

            $pabbly_array = [
                'Medicine Category' => $medicinecategory->name
            ];

            $action = 'New Ward';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
