<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Entities\Advocate;
use Modules\LegalCaseManagement\Entities\Court;
use Modules\LegalCaseManagement\Events\CreateCase;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCaseLis
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
    public function handle(CreateCase $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $case = $event->case;

            $court = Court::find($case->court_id);
            $advocate = User::find($case->advocates);

            $pabbly_array = [
                'Court' => $court->name,
                'Case Type' => $case->casenumber,
                'Case Year' => $case->year,
                'Case Number' => $case->case_number,
                'Case Fileing Date' => $case->filing_date,
                'Case Title' => $case->title,
                'Case Description' => $case->description,
                'Under Acts' => $case->under_acts,
                'Under Section' => $case->under_section,
                'FIR Number' => $case->FIR_number,
                'FIR Year' => $case->FIR_year,
                'Advocate' => $advocate->name,
                'Court Room' => $case->court_room,
                'Opponent Advocate' => $case->opp_adv,
                'stage' => $case->stage,
                'judge' => $case->judge,
                'Police Station' => $case->police_station,
                'Your Party Name' => json_decode($case->your_party_name),
                'Opp Party Name' => json_decode($case->opp_party_name),
            ];

            $action = 'New Case';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
