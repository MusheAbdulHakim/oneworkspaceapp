<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Events\CreateWorkorder;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateWorkorderLis
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
    public function handle(CreateWorkorder $event)
    {
        if (module_is_active('PabblyConnect')) {

            $workorder = $event->workorder;
            $request = $event->request;

            $location  = Location::find($workorder->location_id);
            $component = Component::find($request->components);
            $users     = User::whereIn('id', $request->user)->get();

            if ($users->count() > 1) {
                $userNames = $users->pluck('name')->implode(', ');
            } elseif ($users->count() === 1) {
                $userNames = $users[0]->name;
            } else {
                $userNames = 'No users found';
            }

            $action = 'New Workorder';
            $module = 'CMMS';

            $pabbly_array = array(
                "WO Name"     => $request->wo_name,
                "User Name"   => $userNames,
                "Priority"    => $request->priority,
                "Location"    => $location->name,
                "Component"   => $component->name,
                "Date"        => $request->date,
                "Time"        => $request->time,
                "Instruction" => $request->instructions,
                "Tags"        => $request->tags,
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}