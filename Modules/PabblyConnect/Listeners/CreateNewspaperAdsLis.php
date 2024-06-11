<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Entities\Distribution;
use Modules\Newspaper\Entities\Newspaper;
use Modules\Newspaper\Entities\NewspaperCategory;
use Modules\Newspaper\Events\CreateNewspaperAds;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperAdsLis
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
    public function handle(CreateNewspaperAds $event)
    {
        if (module_is_active('PabblyConnect')) {
            $ad = $event->ad;

            $newspaper = Newspaper::find($ad->newspaper);
            $category = NewspaperCategory::find($ad->category);
            $distributions = Distribution::find($ad->distributions);

            $pabbly_array = [
                'Advertisement Title' => $ad->name,
                'Advertisement Date' => $ad->date,
                'News Paper Title' => $newspaper->name,
                'News Paper Date' => $newspaper->date,
                'News Paper Category' => $category->name,
                'News Paper Distribution Title' => $distributions->name,
                'News Paper Distribution Address' => $distributions->address,
            ];

            $action = 'New Advertisement';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
