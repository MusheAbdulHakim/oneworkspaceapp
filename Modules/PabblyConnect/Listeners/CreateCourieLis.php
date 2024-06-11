<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CourierManagement\Entities\CourierBranch;
use Modules\CourierManagement\Entities\PackageCategory;
use Modules\CourierManagement\Entities\Servicetype;
use Modules\CourierManagement\Events\Couriercreate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourieLis
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
    public function handle(Couriercreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $receiverDetails = $event->receiverDetails;
            $courierPackageInfo = $event->courierPackageInfo;
            $request = $event->request;

            $service_type = Servicetype::find($receiverDetails->service_type);
            $source_branch = CourierBranch::find($receiverDetails->source_branch);
            $destination_branch = CourierBranch::find($receiverDetails->destination_branch);
            $package_category = PackageCategory::find($courierPackageInfo->package_category);

            $pabbly_array = [
                'Sender Name' => $receiverDetails->sender_name,
                'Tracking ID' => $receiverDetails->tracking_id,
                'Sender Mobile Number' => $receiverDetails->sender_mobileno,
                'Sender Email' => $receiverDetails->sender_email,
                'Receiver Name' => $receiverDetails->receiver_name,
                'Receiver Mobile Number' => $receiverDetails->receiver_mobileno,
                'Delivery Address' => $receiverDetails->delivery_address,
                'Service Type' => $service_type->service_type,
                'Source Branch' => $source_branch->branch_name,
                'Destination Branch' => $destination_branch->branch_name,
                'Payment Status' => $receiverDetails->payment_status,
                'Package Title' => $courierPackageInfo->package_title,
                'Package Description' => $courierPackageInfo->package_description,
                'Package Height' => $courierPackageInfo->height,
                'Package Width' => $courierPackageInfo->width,
                'Package Weight' => $courierPackageInfo->weight,
                'Package Category' => $package_category->category,
                'Package Tracking Status' => $courierPackageInfo->tracking_status,
                'Package Price' => $courierPackageInfo->price,
                'Expected Delivery Date' => $courierPackageInfo->expected_delivery_date,
            ];

            $action = 'New Courier';
            $module = 'CourierManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
