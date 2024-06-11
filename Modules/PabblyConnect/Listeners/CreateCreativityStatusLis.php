<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InnovationCenter\Events\CreateCreativityStatus;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCreativityStatusLis
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
    public function handle(CreateCreativityStatus $event)
    {
        if (module_is_active('PabblyConnect')) {
            $Creativitystatus = $event->Creativitystatus;

            $pabbly_array = [
                'Creativity Status' => $Creativitystatus->name,
            ];

            $action = 'New Creativity Status';
            $module = 'InnovationCenter';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
