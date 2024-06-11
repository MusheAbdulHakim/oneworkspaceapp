<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ToDo\Events\ToDoStageSystemSetup;

class ToDoStageSystemSetupLis
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
    public function handle(ToDoStageSystemSetup $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;

            $namesArray = [];

            foreach ($request->stages as $item) {
                $namesArray[] = $item['name'];
            }

            $pabbly_array = [
                'State Titles' => $namesArray
            ];

            $action = 'New To Do Stage';
            $module = 'ToDo';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
