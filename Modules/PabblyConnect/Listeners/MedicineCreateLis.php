<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PharmacyManagement\Entities\MedicineCategory;
use Modules\PharmacyManagement\Entities\MedicineType;
use Modules\PharmacyManagement\Events\MedicineCreate;

class MedicineCreateLis
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
    public function handle(MedicineCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $medicine = $event->medicine;
            $request = $event->request;

            $medicine_category = MedicineCategory::find($medicine->category);
            $medicine_type = MedicineType::find($medicine->type);
            $medicine_manufacturer = User::find($medicine->manufacturer);

            $pabbly_array = [
                'Medicine Name' => $medicine->name,
                'Medicine Category' => $medicine_category->name,
                'Medicine Type' => $medicine_type->name,
                'Medicine Manufacturer Name' => $medicine_manufacturer->name,
                'Medicine Manufacturer Email' => $medicine_manufacturer->email,
                'Unit Price' => $medicine->unit_price,
                'Sale Price' => $medicine->sale_price,
                'Manufacturing Date' => $medicine->manufacturing_date,
                'Expiry Date' => $medicine->expiry_date,
                'Prescription Required' => $medicine->prescription_required == 1 ? 'Required' : 'Not Required',
                'Description' => $medicine->description,
            ];

            $action = 'New Medicine Type';
            $module = 'PharmacyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
