<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Workflow\Entities\Workflowdothis;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Events\CreateWorkflow;

class CreateWorkflowLis
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
    public function handle(CreateWorkflow $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $workflow = $event->Workflow;

            $taskIds = explode(',', $workflow->do_this);

            $do_this = Workflowdothis::WhereIn('id', $taskIds)->get();
            $submodules = $do_this->pluck('submodule')->toArray();
            $do_this_name = implode(', ', $submodules);

            $module_name = WorkflowModule::find($workflow->module_name);

            $zap_array = array(
                "Name" => $workflow->name,
                "Module" => $module_name->module,
                "Event" => $module_name->submodule,
                "Actions" => $do_this_name,
            );

            $action = 'New Workflow';
            $module = 'Workflow';

            PabblySend::SendPabblyCall($module, $zap_array, $action);
        }
    }
}
