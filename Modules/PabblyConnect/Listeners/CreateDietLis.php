<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Events\CreateDiet;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDietLis
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
    public function handle(CreateDiet $event)
    {
        if (module_is_active('PabblyConnect')) {
            $diet = $event->diet;

            $pabbly_array = [
                'Diet Title' => $diet->name
            ];

            $action = 'New Diet';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
