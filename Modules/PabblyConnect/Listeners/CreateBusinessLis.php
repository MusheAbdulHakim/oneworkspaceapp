<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VCard\Events\CreateBusiness;

class CreateBusinessLis
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
    public function handle(CreateBusiness $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $business = $event->business;

            $action = 'New Business';
            $module = 'VCard';
            $pabbly_array = array(
                "Business Title"         => $business['title'],
                "Business Slug"          => $business['slug'],
                "Business Link"          => env('APP_URL') . '/cards/' . $business['slug'],
                "Business Branding Text" => $business['branding_text'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}