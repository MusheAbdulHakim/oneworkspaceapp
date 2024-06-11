<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\StatusChangeProposal;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;

class StatusChangePropsalLis
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
    public function handle(StatusChangeProposal $event)
    {
        if (module_is_active('PabblyConnect')) {
            $proposal = $event->proposal;
            $proposal->status = Proposal::$statues[$proposal->status];
            unset($proposal->customer_id, $proposal->issue_date, $proposal->category_id, $proposal->is_convert, $proposal->converted_invoice_id, $proposal->workspace, $proposal->created_by);
            $action = 'Proposal Status Updated';
            $module = 'general';

            $pabbly_array = array(
                "proposal_id"     => $proposal['proposal_id'],
                "status"          => $proposal['status'],
                "proposal_module" => $proposal['proposal_module'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}