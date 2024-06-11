<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\Depreciation;
use Modules\FixEquipment\Entities\EquipmentCategory;
use Modules\FixEquipment\Entities\EquipmentLocation;
use Modules\FixEquipment\Entities\Manufacturer;
use Modules\FixEquipment\Events\CreateAsset;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixAssetLis
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
    public function handle(CreateAsset $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $asset = $event->asset;

            $location = EquipmentLocation::find($asset->location);
            $user = User::find($asset->supplier);
            $manufacturer = Manufacturer::find($asset->manufacturer);
            $category = EquipmentCategory::find($asset->category);
            $depreciation = Depreciation::find($asset->depreciation_method);

            $pabbly_array = [
                'Asset Title' => $asset->title,
                'Model Title' => $asset->model_name,
                'Serial Number' => $asset->serial_number,
                'Asset Purchase Date' => $asset->purchase_date,
                'Asset Purchase Price' => $asset->purchase_price,
                'Asset Description' => $asset->description,
                'Asset Location' => $location->location_name,
                'Asset Manufacturer' => $manufacturer->title,
                'Asset Category' => $category->title,
                'Asset Depreciation Title' => $depreciation->title,
                'Depreciation Rate' => $depreciation->rate,
                'Asset Supplier' => $user->name,
                'Supplier Email' => $user->email,
                'Supplier Phone Number' => $user->mobile_no,
            ];

            $action = 'New Asset';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
