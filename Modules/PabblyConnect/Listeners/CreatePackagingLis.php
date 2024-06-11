<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeverageManagement\Entities\Manufacturing;
use Modules\BeverageManagement\Events\CreatePackaging;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePackagingLis
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
    public function handle(CreatePackaging $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $packaging = $event->packaging;

            $manufacturer = Manufacturing::find($packaging->manufacturing_id);

            $pabbly_array = [
                'Product Name' => $manufacturer->product_name,
                'Schedule Date' => $manufacturer->schedule_date,
                'Raw Items' => json_decode($request->raw_item_array),
                'Total' => $packaging->total,
            ];

            $action = 'New Packaging';
            $module = 'BeverageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
