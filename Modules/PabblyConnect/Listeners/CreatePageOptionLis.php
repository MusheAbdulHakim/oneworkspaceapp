<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreatePageOption;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePageOptionLis
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
    public function handle(CreatePageOption $event)
    {
        if (module_is_active('Zapier')) {
            $request = $event->request;
            $custom_page = $event->custom_page;

            $pabbly_array = [
                "Custom Page Title" => $custom_page->name,
                "Custom Page Content" => strip_tags($custom_page->contents),
            ];

            $action = 'New Page Option';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
