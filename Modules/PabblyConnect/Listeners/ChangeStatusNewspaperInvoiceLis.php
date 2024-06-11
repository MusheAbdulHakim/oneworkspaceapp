<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\ChangeStatusNewspaperInvoice;
use Modules\PabblyConnect\Entities\PabblySend;

class ChangeStatusNewspaperInvoiceLis
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
    public function handle(ChangeStatusNewspaperInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $newspaperinvoice = $event->newspaperinvoice;

            $agent = User::find($newspaperinvoice->user_id);

            $pabbly_array = [
                'Agent Name' => $agent->name,
                'Agent Email' => $agent->email,
                'Agent Mobile Number' => $agent->mobile_no,
                'Invoice Issue Date' => $newspaperinvoice->issue_date,
                'Invoice Due Date' => $newspaperinvoice->due_date,
                'Invoice Status' => $newspaperinvoice->status == 1 ? 'Posted' : 'Draft',
            ];

            $action = 'Update Newspaper Invoice Status';
            $module = 'Newspaper';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
