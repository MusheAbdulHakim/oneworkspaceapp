<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Events\CreateMaintenance;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMaintenanceLis
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
    public function handle(CreateMaintenance $event)
    {
        if (module_is_active('Zapier')) {
            $request = $event->request;
            $maintenance = $event->maintenance;

            $asset = FixAsset::find($maintenance->asset);

            $pabbly_array = [
                'Asset Title' => $asset->title,
                'Asset Purchase Date' => $asset->purchase_date,
                'Asset Purchase Price' => $asset->purchase_price,
                'Maintenance Type' => $maintenance->maintenance_type,
                'Maintenance Amount' => $maintenance->price,
                'Maintenance Date' => $maintenance->maintenance_date,
                'Maintenance Description' => $maintenance->description,
            ];

            $action = 'New Maintenance';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
