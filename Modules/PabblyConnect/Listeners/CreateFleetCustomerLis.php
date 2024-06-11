<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Events\CreateFleetCustomer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFleetCustomerLis
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
    public function handle(CreateFleetCustomer $event)
    {
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $customer = $event->customers;

            $action = 'New Customer';
            $module = 'Fleet';
            $pabbly_array = array(
                "Customer Name"    => $customer['name'],
                "Customer Email"   => $customer['email'],
                "Contact Number"   => $customer['phone'],
                "Customer Address" => $customer['address'],
            );

            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}