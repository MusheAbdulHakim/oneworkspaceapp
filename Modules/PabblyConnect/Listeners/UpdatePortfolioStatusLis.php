<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Portfolio\Events\UpdatePortfolioStatus;

class UpdatePortfolioStatusLis
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
    public function handle(UpdatePortfolioStatus $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $item = $event->item;

            $pabbly_array = [
                'Title' => $item->title,
                'Portfolio Short Description' => $item->short_description,
                'Portfolio Description' => strip_tags($item->description),
                'Portfolio Status' => $item->enabled == 1 ? 'Enable' : 'Diabled',
            ];

            $action = 'Update Portfolio Status';
            $module = 'Portfolio';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
