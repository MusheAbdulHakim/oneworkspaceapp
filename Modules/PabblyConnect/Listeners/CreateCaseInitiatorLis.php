<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Events\CreateCaseInitiator;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCaseInitiatorLis
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
    public function handle(CreateCaseInitiator $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $caseInitiator = $event->caseInitiator;

            $pabbly_array = [
                'Case Initiator Name' => $caseInitiator->name,
                'Case Initiator Email' => $caseInitiator->email,
                'Case Initiator Type' => $caseInitiator->type,
            ];

            $action = 'New Case Initiator';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
