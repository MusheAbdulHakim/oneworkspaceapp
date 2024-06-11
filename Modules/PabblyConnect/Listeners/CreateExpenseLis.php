<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Entities\Cases;
use Modules\LegalCaseManagement\Events\CreateExpense;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateExpenseLis
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
    public function handle(CreateExpense $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $expense = $event->expense;

            $case = Cases::find($expense->case_id);
            $advocate = User::find($expense->member);

            $pabbly_array = [
                'Case Title' => $case->title,
                'Advocate' => $advocate->name,
                'Date' => $expense->date,
                'Particulars' => $expense->particulars,
                'Money' => $expense->money,
                'Method' => $expense->method,
                'Notes' => $expense->notes
            ];

            $action = 'New Expense';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
