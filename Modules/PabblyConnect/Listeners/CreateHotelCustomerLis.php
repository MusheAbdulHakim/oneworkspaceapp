<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateHotelCustomer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHotelCustomerLis
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
    public function handle(CreateHotelCustomer $event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $customer = $event->customer;

            $pabbly_array = [
                'Customer Name' => $customer->name,
                'Last Name' => $customer->last_name,
                'Email' => $customer->email,
                'Date of Birth' => $customer->dob,
                'Id Number' => $customer->id_number,
                'Company Title' => $customer->company,
                'VAT Number' => $customer->vat_number,
                'Home Phone' => $customer->home_phone,
                'Mobile Phone' => $customer->mobile_phone,
                'Other' => $customer->other
            ];

            $action = 'New Customer';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
