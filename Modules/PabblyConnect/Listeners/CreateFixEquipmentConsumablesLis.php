<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\EquipmentCategory;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Entities\Manufacturer;
use Modules\FixEquipment\Events\CreateConsumables;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixEquipmentConsumablesLis
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
    public function handle(CreateConsumables $event)
    {
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $consumables = $event->consumables;

            $asset = FixAsset::find($consumables->asset);
            $category = EquipmentCategory::find($consumables->category);
            $manufacturer = Manufacturer::find($consumables->manufacturer);

            $pabbly_array = [
                'Consumables Title' => $consumables->title,
                'Consumables Purchase Date' => $consumables->date,
                'Consumables Price' => $consumables->price,
                'Consumables Quantity' => $consumables->quantity,
                'Consumables Manufacturer' => $manufacturer->title,
                'Asset Title' => $asset->title,
                'Asset Purchase Date' => $asset->purchase_date,
                'Asset Category' => $category->title,
            ];

            $action = 'New Consumables';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
