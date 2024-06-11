<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Portfolio\Events\PortfolioCategoryCreate;

class CreatePortfolioCategoryLis
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
    public function handle(PortfolioCategoryCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $portfolioCategory = $event->portfolioCategory;

            $pabbly_array = [
                'Portfolio Category Title' => $portfolioCategory->title,
                'Portfolio Categoey Description' => $portfolioCategory->description
            ];

            $action = 'New Portfolio Category';
            $module = 'Portfolio';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
