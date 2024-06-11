<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Events\CreateWorkrequest;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateWorkRequestLis
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
    public function handle(CreateWorkrequest $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request   = $event->request;
            $workorder = $event->workorder;

            $component = Component::find($request->components_id);

            $action = 'New Workrequest';
            $module = 'CMMS';

            $pabbly_array = array(
                "Work Order Title" => $request->wo_name,
                "Instructions"     => $request->instructions,
                "User Name"        => $request->user_name,
                "User Email"       => $request->user_email,
                "Component"        => $component->name,
                "Priority"         => $workorder['priority'],
                "Work Status"      => $workorder['work_status'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}