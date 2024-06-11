<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PropertyManagement\Entities\Property;
use Modules\PropertyManagement\Entities\PropertyUnit;
use Modules\PropertyManagement\Events\CreatePropertyInvoicePayment;

class CreatePropertyInvoicePaymentLis
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
    public function handle(CreatePropertyInvoicePayment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $invoicePayment = $event->invoicePayment;

            $property = Property::find($invoicePayment->property_id);
            $propertyUnit = PropertyUnit::find($invoicePayment->unit_id);
            $user = User::find($invoicePayment->unit_id);

            $pabbly_array = [
                'Customer Name' => $user->name,
                'Customer Email' => $user->email,
                'Customer Mobile Number' => $user->mobile_no,
                'Property Title' => $property->name,
                'Property Unit Title' => $propertyUnit->name,
                'Invoice Issue Date' => $invoicePayment->issue_date,
                'Invoice Due Date' => $invoicePayment->due_date,
                'Invoice Total Amount' => $invoicePayment->total_amount,
                'Status' => $invoicePayment->status,
            ];

            $action = 'New Property Invoice Payment';
            $module = 'PropertyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
