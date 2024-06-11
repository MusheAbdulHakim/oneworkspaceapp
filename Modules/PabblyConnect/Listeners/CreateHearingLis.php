<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Entities\Cases;
use Modules\LegalCaseManagement\Entities\Court;
use Modules\LegalCaseManagement\Events\CreateHearing;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHearingLis
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
    public function handle(CreateHearing $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $hearing = $event->hearing;

            $case = Cases::find($hearing->case_id);
            $court = Court::find($case->court_id);

            $pabbly_array = [
                'Case Number' => $case->case_number,
                'Case Year' => $case->year,
                'Case Title' => $case->title,
                'Hearing Date' => $hearing->date,
                'Court' => $court->name,
                'Court Type' => $court->type,
                'Court Location' => $court->location,
                'Court Address' => $court->address,
                'Remarks' => $hearing->remark,
            ];

            $action = 'New Hearing';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
