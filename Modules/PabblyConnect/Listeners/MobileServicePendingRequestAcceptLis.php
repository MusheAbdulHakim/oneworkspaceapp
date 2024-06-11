<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MobileServiceManagement\Events\MobileServicePendingRequestAccept;
use Modules\PabblyConnect\Entities\PabblySend;

class MobileServicePendingRequestAcceptLis
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
    public function handle(MobileServicePendingRequestAccept $event)
    {
        if (module_is_active('PabblyConnect')) {
            $findService = $event->findService;

            $pabbly_array = [
                'Service ID' => $findService->service_id,
                'Customer Name' => $findService->customer_name,
                'Customer Email' => $findService->email,
                'Customer Mobile Number' => $findService->mobile_no,
                'Priority' => $findService->priority,
                'Mobile Name' => $findService->mobile_name,
                'Mobile Company' => $findService->mobile_company,
                'Mobile Model' => $findService->mobile_model,
                'Description' => $findService->description,
                'Service Status' => $findService->is_approve == 1 ? 'Approved' : '',
            ];

            $action = 'New Mobile Service Request Accept';
            $module = 'MobileServiceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
