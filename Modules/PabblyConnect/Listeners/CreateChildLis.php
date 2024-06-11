<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Entities\Childcare;
use Modules\ChildcareManagement\Entities\Parents;
use Modules\ChildcareManagement\Events\CreateChild;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateChildLis
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
    public function handle(CreateChild $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $child = $event->child;

            $parent = Parents::find($child->parent_id);
            $childcare = Childcare::find($child->childcare_id);

            $pabbly_array = [
                'Child Care Title' => $childcare->name,
                'Child Care Address' => $childcare->address,
                'Child Care Contact Number' => $childcare->contact_number,
                'First Name' => $child->first_name,
                'Last Name' => $child->last_name,
                'Date of Birth' => $child->dob,
                'Gender' => $child->gender,
                'Age' => $child->age,
                'Parent First Name' => $parent->first_name,
                'Parent Last Name' => $parent->last_name,
                'Parent Email' => $parent->email,
                'Parent Contact Number' => $parent->contact_number,
                'Parent Address' => $parent->address
            ];

            $action = 'New Child';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
