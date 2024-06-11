<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Entities\Cases;
use Modules\LegalCaseManagement\Events\CreateFeeRecieve;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFeeRecieveLis
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
    public function handle(CreateFeeRecieve $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $feeReceive = $event->feeReceive;

            $case = Cases::find($feeReceive->case_id);
            $member = User::find($feeReceive->member);

            $pabbly_array = [
                'Case Title' => $case->title,
                'Date' => $feeReceive->date,
                'Particulars' => $feeReceive->particulars,
                'Money' => $feeReceive->money,
                'Method' => $feeReceive->method,
                'Notes' => $feeReceive->notes,
                'Member' => $member->name
            ];

            $action = 'New Fee Recieve';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
