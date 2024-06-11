<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SalesAgent\Events\SalesAgentCreate;

class CreateSalesAgentLis
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
    public function handle(SalesAgentCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $salesagent = $event->salesagent;

            $pabbly_array = [
                "Name" => $request['name'],
                "Contact" => $request['contact'],
                "Email" => $request['email'],
                "Tax Number" => $request['tax_number'],
                "Billing Name" => $request['billing_name'],
                "Billing Phone" => $request['billing_phone'],
                "Billing Address" => $request['billing_address'],
                "Billing City" => $request['billing_city'],
                "Billing State" => $request['billing_state'],
                "Billing Country" => $request['billing_country'],
                "Billing Zip" => $request['billing_zip'],
                "Shipping Name" => $request['shipping_name'],
                "Shipping Phone" => $request['shipping_phone'],
                "Shipping Address" => $request['shipping_address'],
                "Shipping City" => $request['shipping_city'],
                "Shipping State" => $request['shipping_state'],
                "Shipping Country" => $request['shipping_country'],
                "Shipping Zip" => $request['shipping_zip']
            ];

            $action = 'New Sales Agent';
            $module = 'SalesAgent';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
