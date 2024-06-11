<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PharmacyManagement\Events\MedicineCategoryCreate;

class MedicineCategoryCreateLis
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
    public function handle(MedicineCategoryCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $MedicineCategory = $event->MedicineCategory;

            $pabbly_array = [
                'Medicine Category Title' => $MedicineCategory->name
            ];

            $action = 'New Medicine Category';
            $module = 'PharmacyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
