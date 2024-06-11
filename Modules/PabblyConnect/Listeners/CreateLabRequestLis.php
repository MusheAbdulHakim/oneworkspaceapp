<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Entities\Patient;
use Modules\MedicalLabManagement\Entities\TestContent;
use Modules\MedicalLabManagement\Events\CreateLabRequest;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateLabRequestLis
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
    public function handle(CreateLabRequest $event)
    {
        if (module_is_active('PabblyConnect')) {
            $labRequest = $event->labRequest;
            $request = $event->request;

            $patient = Patient::find($labRequest->patient_id);
            $test_content = TestContent::find($labRequest->content_id);

            $pabbly_array = [
                'Patient First Name' => $patient->first_name,
                'Patient Last Name' => $patient->last_name,
                'Patient Date of Birth' => $patient->dob,
                'Priority' => $labRequest->priority,
                'Date' => $labRequest->date,
                'Test Content Title' => $test_content->name,
                'Test Content Code' => $test_content->code,
            ];

            $action = 'New Lab Request';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
