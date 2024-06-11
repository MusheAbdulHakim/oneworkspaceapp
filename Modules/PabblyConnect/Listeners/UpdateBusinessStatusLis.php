<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VCard\Events\BusinessStatus;

class UpdateBusinessStatusLis
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
    public function handle(BusinessStatus $event)
    {
        if (module_is_active('PabblyConnect')) {
            $status = $event->status;

            $action = 'Business Status Update';
            $module = 'VCard';
            $pabbly_array = array(
                "Business Title"         => $status['title'],
                "Business Slug"          => $status['slug'],
                "Business Link"          => env('APP_URL') . '/cards/' . $status['slug'],
                "Business Status"        => $status['status'],
                "Business Branding Text" => $status['branding_text'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}