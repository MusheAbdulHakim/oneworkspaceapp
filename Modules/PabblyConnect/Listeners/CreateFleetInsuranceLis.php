<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Recurring;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\CreateInsurance;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFleetInsuranceLis
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
    public function handle(CreateInsurance $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $insurance = $event->insurance;

            $vehicle = Vehicle::find($insurance->vehicle_name);
            $recurring = Recurring::find($insurance->scheduled_period);

            $zap_array = [
                'Insurance Provider' => $insurance->insurance_provider,
                'Vehicle Name' => $vehicle->name,
                'Start Date' => $insurance->start_date,
                'End Date' => $insurance->end_date,
                'Schedule Date' => $insurance->scheduled_date,
                'Recurring Period Title' => $recurring->name,
                'Deductible' => $insurance->deductible,
                'Charge Payable' => $insurance->charge_payable,
                'Policy Number' => $insurance->policy_number,
                'Notes' => $insurance->notes
            ];

            $module = 'Fleet';
            $action = 'New Insurance';
            PabblySend::SendPabblyCall($module, $zap_array, $action);
        }
    }
}
