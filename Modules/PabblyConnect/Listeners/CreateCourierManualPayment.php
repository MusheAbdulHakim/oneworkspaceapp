<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CourierManagement\Entities\CourierBranch;
use Modules\CourierManagement\Entities\Servicetype;
use Modules\CourierManagement\Events\Manualpaymentdatastore;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourierManualPayment
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
    public function handle(Manualpaymentdatastore $event)
    {
        if (module_is_active('PabblyConnect')) {
            $courierPayment = $event->courierPayment;
            $receiverDetails = $event->receiverDetails;
            $request = $event->request;

            $service_type = Servicetype::find($receiverDetails->service_type);
            $source_branch = CourierBranch::find($receiverDetails->source_branch);
            $destination_branch = CourierBranch::find($receiverDetails->destination_branch);

            $pabbly_array = [
                'Tracking ID' => $courierPayment->tracking_id,
                'Sender Name' => $receiverDetails->sender_name,
                'Sender Mobile Number' => $receiverDetails->sender_mobileno,
                'Sender Email' => $receiverDetails->sender_email,
                'Delivery Address' => $receiverDetails->delivery_address,
                'Reciver Name' => $receiverDetails->receiver_name,
                'Reviver Mobile Number' => $receiverDetails->receiver_mobileno,
                'Courier Service Type' => $service_type->service_type,
                'Courier Source Branch' => $source_branch->branch_name,
                'Destination Branch' => $destination_branch->branch_name,
                'Payment Method' => $receiverDetails->payment_type,
                'Payment Status' => $receiverDetails->payment_status,
            ];

            $action = 'New Courier Payment';
            $module = 'CourierManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
