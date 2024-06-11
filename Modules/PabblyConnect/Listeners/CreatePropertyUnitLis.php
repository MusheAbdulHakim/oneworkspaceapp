<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PropertyManagement\Entities\Property;
use Modules\PropertyManagement\Events\CreatePropertyUnit;

class CreatePropertyUnitLis
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
    public function handle(CreatePropertyUnit $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $propertyUnit = $event->propertyUnit;

            $property = Property::find($propertyUnit->property_id);

            $pabbly_array = [
                'Property Title' => $property->name,
                'Property Unit title' => $propertyUnit->name,
                'Unit Bedroom' => $propertyUnit->bedroom,
                'Unit Bathrooms' => $propertyUnit->baths,
                'Unit Kitchen' => $propertyUnit->kitchen,
                'Unit Amenities' => $propertyUnit->amenities,
                'Unit Rent Type' => $propertyUnit->rent_type,
                'Unit Rent' => $propertyUnit->rent,
                'Utilities Included' => $propertyUnit->utilities_included,
                'Unit Description' => $propertyUnit->description,
            ];

            $action = 'New Property Units';
            $module = 'PropertyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
