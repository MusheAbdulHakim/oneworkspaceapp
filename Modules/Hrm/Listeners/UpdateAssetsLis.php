<?php

namespace Modules\Hrm\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Assets\Entities\Asset;

class UpdateAssetsLis
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
    public function handle($event)
    {
        $request = $event->request;
        $asset = $event->asset;

        $assets     = Asset::where('user_id',$asset->id)->first();
        if(!empty($assets))
        {
            $assets->branch = $request->branch;
            $assets->save();
        }
    }
}
