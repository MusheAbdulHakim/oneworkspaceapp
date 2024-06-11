<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\AssetComponents;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Events\CreatePreDefinedKit;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePreDefinedKitLis
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
    public function handle(CreatePreDefinedKit $event)
    {
        if (module_is_active('PabblyConnect')) {

            $kit = $event->kit;
            $request = $event->request;

            $asset = FixAsset::find($kit->asset);
            $component = AssetComponents::find($kit->component);

            $pabbly_array = [
                'Pre Defined Kit Title' => $kit->title,
                'Asset Title' => $asset->title,
                'Asset Purchase Date' => $asset->purchase_date,
                'Component Title' => $component->title,
            ];

            $action = 'New Pre Defined Kit';
            $module = 'FixEquipment';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
