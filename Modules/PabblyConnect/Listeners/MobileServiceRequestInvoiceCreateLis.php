<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MobileServiceManagement\Events\MobileServiceRequestInvoiceCreate;
use Modules\PabblyConnect\Entities\PabblySend;

class MobileServiceRequestInvoiceCreateLis
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
    public function handle(MobileServiceRequestInvoiceCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $mobileRepairInvoice = $event->mobileRepairInvoice;
            $updateInvoiceStatus = $event->updateInvoiceStatus;

            $pabbly_array = [
                'Service ID' => $mobileRepairInvoice->service_id,
                'Customer Name' => $updateInvoiceStatus->customer_name,
                'Customer Email' => $updateInvoiceStatus->email,
                'Customer Mobile Number' => $updateInvoiceStatus->mobile_no,
                'Priority' => $updateInvoiceStatus->priority,
                'Mobile Name' => $updateInvoiceStatus->mobile_name,
                'Mobile Company' => $updateInvoiceStatus->mobile_company,
                'Description' => $updateInvoiceStatus->description,
                'Repair Charge' => $mobileRepairInvoice->repair_charge,
            ];

            $action = 'New Mobile Service Invoice';
            $module = 'MobileServiceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
