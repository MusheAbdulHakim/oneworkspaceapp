<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MobileServiceManagement\Events\MobileServiceAssignTechnician;
use Modules\PabblyConnect\Entities\PabblySend;

class MobileServiceAssignTechnicianLis
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
    public function handle(MobileServiceAssignTechnician $event)
    {
        if (module_is_active('PabblyConnect')) {
            $technicianData = $event->technicianData;
            $mobileServiceReq = $event->mobileServiceReq;

            $technician = User::find($technicianData['technician_id']);

            $pabbly_array = [
                'Service ID' => $mobileServiceReq->service_id,
                'Customer Name' => $mobileServiceReq->customer_name,
                'Customer Email' => $mobileServiceReq->email,
                'Priority' => $mobileServiceReq->priority,
                'Mobile Name' => $mobileServiceReq->mobile_name,
                'Mobile Company' => $mobileServiceReq->mobile_company,
                'Mobile Model' => $mobileServiceReq->mobile_model,
                'Technician Name' => $technician->name,
                'Technician Email' => $technician->email
            ];

            $action = 'New Mobile Service Technician Assign';
            $module = 'MobileServiceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
