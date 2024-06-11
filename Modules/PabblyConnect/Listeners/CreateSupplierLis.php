<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Events\CreateSupplier;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSupplierLis
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
    public function handle(CreateSupplier $event)
    {
        if (module_is_active('PabblyConnect')) {
            $supplier = $event->suppliers;
            $request = $event->request;

            $action = 'New Supplier';
            $module = 'CMMS';

            $pabbly_array = array(
                "Supplier Name" => $supplier['name'],
                "Contact"       => $supplier['contact'],
                "Email"         => $supplier['email'],
                "Phone"         => $supplier['phone'],
                "Address"       => $supplier['address']
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}