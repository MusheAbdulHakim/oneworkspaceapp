<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Events\CreateVendor;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateVendorLis
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
    public function handle(CreateVendor $event)
    {
        if (module_is_active('PabblyConnect')) {
            $vendor = $event->vendor;
            unset($vendor->user_id, $vendor->password);
            $action = 'New Vendor';
            $module = 'Account';

            $pabbly_array = array(
                "id"              => $vendor['id'],
                "vendor_id"       => $vendor['vendor_id'],
                "name"            => $vendor['name'],
                "contact"         => $vendor['contact'],
                "lang"            => $vendor['lang'],
                "workspace"       => $vendor['workspace'],
                "email"           => $vendor['email'],
                "tax_number"      => $vendor['tax_number'],
                "billing_name"    => $vendor['billing_name'],
                "billing_country" => $vendor['billing_country'],
                "billing_state"   => $vendor['billing_state'],
                "billing_city"    => $vendor['billing_city'],
                "billing_phone"   => $vendor['billing_phone'],
                "billing_address" => $vendor['billing_address'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}