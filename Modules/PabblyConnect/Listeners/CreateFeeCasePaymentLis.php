<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Events\CreateFeePayment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFeeCasePaymentLis
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
            $feePayment = $event->feePayment;

            $advocate = User::find($feePayment->advocate);
            $bill_to = User::find($feePayment->bill_to);

            $pabbly_array = [
                'Bill From' => $feePayment->bill_from,
                'Advocate' => $advocate->name,
                'Bill To' => $bill_to->name,
                'Bill Title' => $feePayment->title,
                'Bill Number' => $feePayment->bill_number,
                'Payment Amount' => $request->amount,
                'Payment Method' => $request->method,
                'Payment Date' => $request->date,
                'Payment Note' => $request->note,
                'Description' => $feePayment->description,
                'Total Amount' => $feePayment->total_amount,
                'Due Amount' => $feePayment->due_amount,
                'Payment Status' => $feePayment->status,
            ];

            $action = 'New Fee Payment';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
