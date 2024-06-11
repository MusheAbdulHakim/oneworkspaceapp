<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PharmacyManagement\Entities\MedicineCategory;
use Modules\PharmacyManagement\Events\MedicineTypeCreate;

class MedicineTypeCreateLis
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
    public function handle(MedicineTypeCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $medicineType = $event->medicineType;
            $medicine_category = MedicineCategory::find($medicineType->medicine_category);

            $pabbly_array = [
                'Medicine Type Title' => $medicineType->name,
                'Medicine Type Category' => $medicine_category->name,
                'Medicine Type Description' => $medicineType->description
            ];

            $action = 'New Medicine Type';
            $module = 'PharmacyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
