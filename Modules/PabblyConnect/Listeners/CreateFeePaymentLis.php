<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Entities\Child;
use Modules\ChildcareManagement\Entities\ChildFee;
use Modules\ChildcareManagement\Events\CreateFeePayment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFeePaymentLis
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
    public function handle(CreateFeePayment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $childFeePayment = $event->childFeePayment;

            $childFee = ChildFee::find($childFeePayment->childfee_id);
            $child = Child::find($childFee->child_id);

            $pabbly_array = [
                'Child First Name' => $child->first_name,
                'Child Last Name' => $child->last_name,
                'Payment Date' => $childFeePayment->date,
                'Payment Amount' => $childFeePayment->amount,
                'Payment Method' => $childFeePayment->method,
                'Total Fee' => $childFee->amount,
                'Payment Status' => $childFeePayment->status,
                'Notes' => $childFeePayment->note
            ];

            $action = 'New Fee Payment';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
