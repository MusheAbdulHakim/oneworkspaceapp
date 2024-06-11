<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperTypeLis
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
    public function handle(CreateNewspaperType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $newspapertype = $event->newspapertype;

            $pabbly_array = [
                'News Paper Type' => $newspapertype->name
            ];

            $action = 'New Newspaper Type';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
