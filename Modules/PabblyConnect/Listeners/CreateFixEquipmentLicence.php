<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\EquipmentCategory;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Events\CreateLicence;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixEquipmentLicence
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
    public function handle(CreateLicence $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $license = $event->license;

            $asset = FixAsset::find($license->asset);
            $category = EquipmentCategory::find($license->category);

            $pabbly_array = [
                'Asset Title' => $asset->title,
                'Asset Purchase Date' => $asset->purchase_date,
                'License Title' => $license->title,
                'License Category' => $category->title,
                'License Number' => $request->license_number,
                'License Purchase Date' => $request->purchase_date,
                'License Expire Date' => $request->expire_date,
                'License Purchase Price' => $request->purchase_price,
            ];

            $action = 'New Licence';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
