<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CateringManagement\Events\CreateSelection;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSelectionLis
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
    public function handle(CreateSelection $event)
    {
        if (module_is_active('PabblyConnect')) {
            $selection = $event->selection;

            $pabbly_array = [
                'Item Title' => $selection->name,
                'Item Type' => $selection->type,
                'Item Price' => $selection->price,
            ];

            $action = 'New Menu Items';
            $module = 'CateringManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
