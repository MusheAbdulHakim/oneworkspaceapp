<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperVarient;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperVarientLis
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
    public function handle(CreateNewspaperVarient $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $newspapervariant = $event->newspapervariant;

            $pabbly_array = [
                'New Paper Varient' => $newspapervariant->name,
                'Varient Price' => $newspapervariant->price,
            ];

            $action = 'New Newspaper Category';
            $module = 'Newspaper';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
