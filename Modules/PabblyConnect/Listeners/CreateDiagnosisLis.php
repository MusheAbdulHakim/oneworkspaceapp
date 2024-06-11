<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MachineRepairManagement\Events\CreateDiagnosis;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDiagnosisLis
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
    public function handle(CreateDiagnosis $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $invoice = $event->invoice;

            $pabbly_array = [
                'Customer Name' => $invoice->customer_name,
                'Customer Email' => $invoice->customer_email,
                'Invoice issue date' => $invoice->issue_date,
                'Invoice Due Date' => $invoice->due_date,
                'Estimated Time' => $invoice->estimated_time,
                'Service Charge' => $invoice->service_charge
            ];

            $action = 'New Diagnosis';
            $module = 'MachineRepairManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
