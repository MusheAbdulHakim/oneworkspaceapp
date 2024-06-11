<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Events\CreateTestUnit;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateTestUnitLis
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
    public function handle(CreateTestUnit $event)
    {
        if (module_is_active('PabblyConnect')) {
            $testUnit = $event->testUnit;

            $pabbly_array = [
                'Test Title' => $testUnit->name,
                'Test Code' => $testUnit->code,
            ];

            $action = 'New Test Unit';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
