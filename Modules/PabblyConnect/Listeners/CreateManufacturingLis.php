<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeverageManagement\Entities\RawMaterial;
use Modules\BeverageManagement\Events\CreateManufacturing;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateManufacturingLis
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
    public function handle(CreateManufacturing $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $manufacturing = $event->manufacturing;

            $raw_material = [];

            for ($i = 0; $i < count($request->raw_quantity); $i++) {
                $raw_material_name = RawMaterial::find($request->raw_material[$i])->name;
                $raw_material[] = [
                    'raw_quantity' => $request->raw_quantity[$i],
                    'raw_material' => $raw_material_name,
                    'raw_unit' => $request->raw_unit[$i],
                ];
            }

            $pabbly_array = [
                'Product Name' => $manufacturing->product_name,
                'Schedule Date' => $manufacturing->schedule_date,
                'Raw Material' => $raw_material,
                'Total Amount' => $manufacturing->total
            ];

            $action = 'New Manufacturing';
            $module = 'BeverageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
