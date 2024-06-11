<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Events\CreateProject;

class CreateProjectLis
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
    public function handle(CreateProject $event)
    {
        if (module_is_active('PabblyConnect')) {
            $project = $event->project;
            $request = $event->request;
            $project->users_list = User::whereIN('email', $request->users_list)->get()->pluck('name')->toArray();
            $action = 'New Project';
            $module = 'Taskly';
            $pabbly_array = array(
                "Project Name"  => $project['name'],
                "Description"   => $project['description'],
                "Start Date"    => $project['start_date'],
                "End Date"      => $project['end_date'],
                "Project Users" => $request->users_list,
            );
            $status = PabblySend::SendPabblyCall($module, $pabbly_array, $action);
            if ($status == false) {
                return redirect()->route('projects.index')->with('success', __('Project Created Successfully!') . ('<br> <span class="text-danger"> ' . __('Webhook call failed.') . '</span>'));
            }
        }
    }
}