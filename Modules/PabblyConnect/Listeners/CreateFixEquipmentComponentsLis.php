<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\EquipmentCategory;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Events\CreateComponent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixEquipmentComponentsLis
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
    public function handle(CreateComponent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $component = $event->component;

            $asset = FixAsset::find($component->asset);
            $category = EquipmentCategory::find($component->category);

            $pabbly_array = [
                'Component Title' => $component->title,
                'Component Quantity' => $component->quantity,
                'Component Price' => $component->price,
                'Component Category' => $category->title,
                'Asset Title' => $asset->title,
                'Asset Purchase Date' => $asset->purchase_date,
            ];

            $action = 'New Component';
            $module = 'FixEquipment';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
