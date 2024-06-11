<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\WasteManagement\Entities\WasteCategory;
use Modules\WasteManagement\Entities\WasteCategoryType;
use Modules\WasteManagement\Entities\WasteLocation;
use Modules\WasteManagement\Entities\WastePickupPoints;
use Modules\WasteManagement\Events\WasteInspectionStatusUpdate;

class WasteInspectionStatusUpdateLis
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
    public function handle(WasteInspectionStatusUpdate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $WasteCollection = $event->WasteCollection;

            $pickup_point = WastePickupPoints::find($WasteCollection->pickup_point_id);
            $location = WasteLocation::find($WasteCollection->location_id);
            $category_type = WasteCategoryType::find($WasteCollection->category_type_id);
            $category = WasteCategory::find($WasteCollection->category_id);

            $pabbly_array = [
                'Name' => $WasteCollection->name,
                'Request ID' => $WasteCollection->request_id,
                'Email' => $WasteCollection->email,
                'Phone Number' => $WasteCollection->phone,
                'Date' => $WasteCollection->date,
                'Status' => !empty($request == 1) ? 'Won' : '',
                'Location' => $location->name,
                'PickUp Point' => $pickup_point->name,
                'Category' => $category->name,
                'Category Type' => $category_type->name,
            ];

            $action = 'Update Waste Inspection Status';
            $module = 'WasteManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
