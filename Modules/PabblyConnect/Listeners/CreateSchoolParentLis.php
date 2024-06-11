<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Events\CreateSchoolParent;

class CreateSchoolParentLis
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
    public function handle(CreateSchoolParent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $parent = $event->parent;

            $pabbly_array = [
                'Parent Name' => $parent->name,
                'Parent Gender' => $parent->gender,
                'Parent Date of Birth' => $parent->date_of_birth,
                'Parent Relation' => $parent->relation,
                'Parent Address' => $parent->address,
                'Parent State' => $parent->state,
                'Parent City' => $parent->city,
                'Parent Zip Code' => $parent->zip_code,
                'Parent Contact' => $parent->contact,
                'Parent Email' => $parent->email,
            ];

            $action = 'New Parents';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
