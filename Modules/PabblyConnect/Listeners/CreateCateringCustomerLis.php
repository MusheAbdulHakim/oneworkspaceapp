<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CateringManagement\Entities\CateringEvents;
use Modules\CateringManagement\Events\CreateCateringCustomer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCateringCustomerLis
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
    public function handle(CreateCateringCustomer $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $CateringCustomer = $event->CateringCustomer;

            $event_type = CateringEvents::find($CateringCustomer->event_type);

            $pabbly_array = [
                'Customer Name' => $CateringCustomer->name,
                'Customer Email' => $CateringCustomer->email,
                'Customer Phone Number' => $CateringCustomer->phone_no,
                'Comapany Name' => $CateringCustomer->comapny_name,
                'Event Type' => $event_type->name,
                'Event Date' => $CateringCustomer->event_date,
                'Number of Guests' => $CateringCustomer->number_of_guests,
                'Billing Address' => $CateringCustomer->billing_address,
                'preferences' => $CateringCustomer->preferences,
            ];

            $action = 'New Customer';
            $module = 'CateringManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
