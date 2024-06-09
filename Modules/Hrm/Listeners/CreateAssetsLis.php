<?php

namespace Modules\Hrm\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Assets\Entities\Asset;

class CreateAssetsLis
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
        $asset = Asset::find($event->assets->id);

        if($asset){
            $asset->branch = $event->request->branch ? $event->request->branch :'';
            $asset->save();
        }
    }
}
