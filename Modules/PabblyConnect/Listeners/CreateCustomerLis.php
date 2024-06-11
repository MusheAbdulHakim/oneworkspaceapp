<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Events\CreateCustomer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCustomerLis
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
    public function handle(CreateCustomer $event)
    {
        if (module_is_active('PabblyConnect')) {
            $customer = $event->customer;
            unset($customer->customer_id, $customer->user_id, $customer->password);

            $action = 'New Customer';
            $module = 'Account';

            $pabbly_array = array(
                "id"               => $customer['id'],
                "user_id"          => $customer['user_id'],
                "customer_id"      => $customer['customer_id'],
                "name"             => $customer['name'],
                "contact"          => $customer['contact'],
                "email"            => $customer['email'],
                "tax_number"       => $customer['tax_number'],
                "billing_name"     => $customer['billing_name'],
                "billing_country"  => $customer['billing_country'],
                "billing_state"    => $customer['billing_state'],
                "billing_city"     => $customer['billing_city'],
                "billing_phone"    => $customer['billing_phone'],
                "billing_zip"      => $customer['billing_zip'],
                "billing_address"  => $customer['billing_address'],
                "shipping_name"    => $customer['shipping_name'],
                "shipping_country" => $customer['shipping_country'],
                "shipping_state"   => $customer['shipping_state'],
                "shipping_cit y"   => $customer['shipping_city'],
                "shipping_phone"   => $customer['shipping_phone'],
                "shipping_zip"     => $customer['shipping_zip'],
                "shipping_address" => $customer['shipping_address'],
                "lang"             => $customer['lang'],
                "workspace"        => $customer['workspace'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}