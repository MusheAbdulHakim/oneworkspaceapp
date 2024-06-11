<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Events\CreateLabTest;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateLabTestLis
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
    public function handle(CreateLabTest $event)
    {
        if (module_is_active('PabblyConnect')) {
            $labTest = $event->labTest;

            $pabbly_array = [
                'Lab Test Title' => $labTest->name,
                'Lab Test Price' => $labTest->cost,
                'Lab Tests' => json_decode($labTest->items)
            ];

            $action = 'New Lab Test';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
