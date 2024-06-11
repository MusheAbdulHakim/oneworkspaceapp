<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperJournalist;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperJournalistLis
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
    public function handle(CreateNewspaperJournalist $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $user = $event->user;
            $journalistDetails = $event->journalistDetails;

            $pabbly_array = [
                'Journalist Name' => $user->name,
                'Journalist Email' => $user->email,
                'Journalist Phone Number' => $user->mobile_no,
                'Journalist Area' => $journalistDetails->area,
                'Journalist City' => $journalistDetails->city,
                'Journalist Address' => $journalistDetails->address
            ];

            $action = 'New Journalist';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
