<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Sage\Events\CreateLedgerAccount;

class CreateLedgerAccountLis
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
    public function handle(CreateLedgerAccount $event)
    {
        if (module_is_active('PabblyConnect')) {
            $sageLedgerAccount = $event->sageLedgerAccount;

            $pabbly_array = [
                'Ledger Account Title' => $sageLedgerAccount->name
            ];

            $action = 'New Ledger Account';
            $module = 'Sage';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
