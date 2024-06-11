<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Events\CreateFreightCustomer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightCustomerLis
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
    public function handle(CreateFreightCustomer $event)
    {
        if (module_is_active('PabblyConnect')) {
            $customer = $event->customer;

            $pabbly_array = [
                'Customer Name' => $customer->name,
                'Customer Email' => $customer->email,
                'Country' => $customer->country,
                'State' => $customer->state,
                'City' => $customer->city,
                'Zip' => $customer->zip,
                'Address' => $customer->address,
            ];

            $action = 'New Freight Customer';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
