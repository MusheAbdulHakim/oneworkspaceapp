<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\ChartOfAccount;
use Modules\DoubleEntry\Events\CreateJournalAccount;
use Modules\PabblyConnect\Entities\PabblySend;

class DoubleEntryLis
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
    public function handle(CreateJournalAccount $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $journal = $event->journal;

            $accounts = $request->accounts;
            $accountIds = array_column($accounts, 'account');
            $accountNames = ChartOfAccount::whereIn('id', $accountIds)->pluck('name', 'id')->all();

            foreach ($accounts as &$entry) {
                $accountId = $entry['account'];

                if (isset($accountNames[$accountId])) {
                    $entry['account_name'] = $accountNames[$accountId];
                } else {
                    $entry['account_name'] = 'Unknown Account';
                }
            }

            $journal['account_data'] = $accounts;

            $pabbly_array = array(
                "Date" => $journal->date,
                "Reference" => $journal->reference,
                "Description" => $journal->description,
                "Account Data" => $accounts,
            );

            $action = 'New Journal Entry';
            $module = 'DoubleEntry';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
