<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Events\CreateDepreciation;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDepreciationLis
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
    public function handle(CreateDepreciation $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $depreciation = $event->depreciation;

            $zap_array = [
                'Depreciation Title' => $depreciation->title,
                'Depteciation Rate' => $depreciation->rate
            ];

            $action = 'New Depreciation';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $zap_array, $action);
        }
    }
}
