<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperJournalistType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperJournalistTypeLis
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
    public function handle(CreateNewspaperJournalistType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $journalisttype = $event->journalisttype;

            $pabbly_array = [
                'Journalist Type Title' => $journalisttype->name,
                'Journalist Price' => $journalisttype->price,
            ];

            $action = 'New Journalist Type';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
