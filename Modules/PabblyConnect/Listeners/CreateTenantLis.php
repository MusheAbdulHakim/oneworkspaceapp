<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PropertyManagement\Entities\Property;
use Modules\PropertyManagement\Entities\PropertyUnit;
use Modules\PropertyManagement\Events\CreateTenant;

class CreateTenantLis
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
    public function handle(CreateTenant $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $tenant = $event->tenant;

            $property = Property::find($tenant->property_id);
            $propertyUnit = PropertyUnit::find($tenant->unit_id);
            $user = User::find($tenant->unit_id);

            $pabbly_array = [
                'Customer Name' => $user->name,
                'Customer Email' => $user->email,
                'Customer Mobile Number' => $user->mobile_no,
                'Property Title' => $property->name,
                'Property Unit Title' => $propertyUnit->name,
                'Total Family Members' => $tenant->total_family_member,
                'Country' => $tenant->country,
                'State' => $tenant->state,
                'City' => $tenant->city,
                'Pincode' => $tenant->pincode,
                'Address' => $tenant->address,
                'Lease Start Date' => $tenant->lease_start_date,
                'Lease End Date' => $tenant->lease_end_date,
            ];

            $action = 'New Property Units';
            $module = 'PropertyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
