<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Entities\FixAsset;
use Modules\FixEquipment\Events\CreateAudit;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAuditLis
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
    public function handle(CreateAudit $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $audit = $event->audit;

            $assetIds = explode(',', $audit['asset']);
            $assets = FixAsset::WhereIn('id', $assetIds)->get()->pluck('title');
            $assetArray = $assets->toArray();

            $titlesAndQuantitiesArray = [];

            foreach ($audit['audit_data'] as $item) {
                $title = $item->title;
                $quantity = $item->quantity;

                $titlesAndQuantitiesArray[] = [
                    'title' => $title,
                    'quantity' => $quantity,
                ];
            }

            $pabbly_array = [
                'Audit Title' => $audit->audit_title,
                'Audit Date' => $audit->audit_date,
                'Audit Status' => $audit->audit_status,
                'Assets' => $assetArray,
                'Audit Data' => $titlesAndQuantitiesArray,
            ];

            $action = 'New Audit';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
