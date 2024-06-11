<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\EquipmentCategory;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Entities\Manufacturer;
use Modules\FixEquipment\Events\CreateAccessories;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAccessoriesLis
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
    public function handle(CreateAccessories $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $accessories = $event->accessories;

            $asset = FixAsset::find($accessories->asset);
            $category = EquipmentCategory::find($accessories->category);
            $manufacturer = Manufacturer::find($accessories->manufacturer);
            $supplier = User::find($accessories->supplier);

            $pabbly_array = [
                'Asset Title' => $asset->title,
                'Asset Purchase Date' => $asset->purchase_date,
                'Accessories Title' => $accessories->title,
                'Accessories Category' => $category->title,
                'Accessories Manufacturer' => $manufacturer->title,
                'Accessories Price' => $accessories->price,
                'Accessories Quantity' => $accessories->quantity,
                'Supplier Name' => $supplier->name,
                'Supplier Email' => $supplier->email,
                'Supplier Mobile Number' => $supplier->mobile_no,
            ];

            $action = 'New Accessories';
            $module = 'FixEquipment';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
