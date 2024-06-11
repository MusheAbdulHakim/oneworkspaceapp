<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Events\CreateBodyPart;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBodyPartLis
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
    public function handle(CreateBodyPart $event)
    {
        if (module_is_active('PabblyConnect')) {
            $bodypart = $event->bodypart;

            $pabbly_array = [
                'Body Part Title' => $bodypart->name
            ];

            $action = 'New Body Part';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
