<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InnovationCenter\Events\CreateCreativityStage;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCreativityStageLis
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
    public function handle(CreateCreativityStage $event)
    {
        if (module_is_active('PabblyConnect')) {
            $Creativitystages = $event->Creativitystages;

            $pabbly_array = [
                'Creativity Stage Title' => $Creativitystages->name,
            ];

            $action = 'New Creativity Stage';
            $module = 'InnovationCenter';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
