<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Portfolio\Entities\PortfolioCategory;
use Modules\Portfolio\Events\CreatePortfolio;

class CreatePortfolioLis
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
    public function handle(CreatePortfolio $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $portfolio = $event->portfolio;

            $category = PortfolioCategory::find($portfolio->category);

            $pabbly_array = [
                'Portfolio Title' => $portfolio->title,
                'Portfolio Category' => $category->title,
                'Portfolio Short Description' => $portfolio->short_description,
                'Portfolio Description' => strip_tags($portfolio->description),
            ];

            $action = 'New Portfolio';
            $module = 'Portfolio';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
