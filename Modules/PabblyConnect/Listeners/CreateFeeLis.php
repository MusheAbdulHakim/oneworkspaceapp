<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Entities\Child;
use Modules\ChildcareManagement\Events\CreateFee;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFeeLis
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
    public function handle(CreateFee $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $childFee = $event->childFee;

            $child = Child::find($childFee->child_id);

            $pabbly_array = [
                'Child First Name' => $child->first_name,
                'Child Last Name' => $child->last_name,
                'Start Date' => $childFee->start_date,
                'Due Date' => $childFee->due_date,
                'Items' => json_decode($childFee->items),
                'Amount' => $childFee->amount,
                'Due Amount' => $childFee->due_amount,
                'Status' => $childFee->status,
                'Notes' => $childFee->notes
            ];

            $action = 'New Fee';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
