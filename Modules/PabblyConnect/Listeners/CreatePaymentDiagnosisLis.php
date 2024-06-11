<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MachineRepairManagement\Events\CreatePaymentDiagnosis;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePaymentDiagnosisLis
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
    public function handle(CreatePaymentDiagnosis $event)
    {
        if (module_is_active('PabblyConnect')) {
            $invoice = $event->invoice;
            $payment = $event->payment;

            $pabbly_array = [
                'Customer Name' => $invoice->customer_name,
                'Customer Email' => $invoice->customer_email,
                'Invoice Issue Date' => $invoice->issue_date,
                'Invoice Due Date' => $invoice->due_date,
                'Payment Date' => $payment->date,
                'Payment Amount' => $payment->amount,
                'Payment Reference' => $payment->reference,
                'Payment Description' => $payment->description,
            ];

            $action = 'New Diagnosis Payment';
            $module = 'MachineRepairManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
