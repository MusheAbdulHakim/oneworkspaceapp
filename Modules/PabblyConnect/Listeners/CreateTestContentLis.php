<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Events\CreateTestContent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateTestContentLis
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
    public function handle(CreateTestContent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $testContent = $event->testContent;

            $pabbly_array = [
                'Test Content Title' => $testContent->name,
                'Test Content Code' => $testContent->code
            ];

            $action = 'New Test Content';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
