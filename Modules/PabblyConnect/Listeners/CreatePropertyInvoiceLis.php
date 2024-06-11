<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PropertyManagement\Entities\Property;
use Modules\PropertyManagement\Entities\PropertyUnit;
use Modules\PropertyManagement\Events\CreatePropertyInvoice;

class CreatePropertyInvoiceLis
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
    public function handle(CreatePropertyInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $propertyInvoice = $event->propertyInvoice;

            $property = Property::find($propertyInvoice->property_id);
            $propertyUnit = PropertyUnit::find($propertyInvoice->unit_id);
            $user = User::find($propertyInvoice->unit_id);

            $pabbly_array = [
                'Customer Name' => $user->name,
                'Customer Email' => $user->email,
                'Customer Mobile Number' => $user->mobile_no,
                'Property Title' => $property->name,
                'Property Unit Title' => $propertyUnit->name,
                'Invoice Issue Date' => $propertyInvoice->issue_date,
                'Invoice Due Date' => $propertyInvoice->due_date,
                'Invoice Total Amount' => $propertyInvoice->total_amount,
                'Status' => $propertyInvoice->status,
            ];

            $action = 'New Property Invoice';
            $module = 'PropertyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
