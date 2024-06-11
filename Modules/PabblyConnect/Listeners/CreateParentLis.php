<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Events\CreateParent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateParentLis
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
    public function handle(CreateParent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $parent = $event->parent;

            $pabbly_array = [
                'Parent First Name' => $parent->first_name,
                'Parent Last Name' => $parent->last_name,
                'Email' => $parent->email,
                'Contact Number' => $parent->contact_number,
                'Parent Address' => $parent->address,
            ];

            $action = 'New Parent';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
