<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Entities\MedicineCategory;
use Modules\HospitalManagement\Events\CreateHospitalMedicine;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHospitalMedicineLis
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
    public function handle(CreateHospitalMedicine $event)
    {
        if (module_is_active('PabblyConnect')) {
            $medicine = $event->medicine;

            $manufacturer = User::find($medicine->manufacturer_id);
            $medicine_category = MedicineCategory::find($medicine->medicine_categories);

            $pabbly_array = [
                'Manufacturer Name' => $manufacturer->name,
                'Manufacturer Email' => $manufacturer->email,
                'Manufacturer Mobile Number' => $manufacturer->mobile_no,
                'Medicine Name' => $medicine->name,
                'Medicine Unit' => $medicine->unit,
                'Medicine Expire Date' => $medicine->expiration_date,
                'Medicine Price per Unit' => $medicine->price_per_unit,
                'Medicine Category Title' => $medicine_category->name,
                'Dosage' => $medicine->dosage,
                'Medicine Quantity Available' => $medicine->quantity_available,
                'Medicine Side Effect' => $medicine->side_effects,
                'Medicine Description' => $medicine->description,
            ];

            $action = 'New Hospital Medicine';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
