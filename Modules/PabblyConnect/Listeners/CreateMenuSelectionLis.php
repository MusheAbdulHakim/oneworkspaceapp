<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CateringManagement\Entities\CateringSelection;
use Modules\CateringManagement\Events\CreateMenuSelection;
use Modules\Zapier\Entities\SendZap;

class CreateMenuSelectionLis
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
    public function handle(CreateMenuSelection $event)
    {
        if (module_is_active('Zapier')) {
            $menusection = $event->menusection;
            $request = $event->request;

            $selectionIds = explode(',', $menusection['selection_id']);

            $selections = CateringSelection::whereIn('id', $selectionIds)->get();

            $selectionData = collect($selections)->map(function ($selection) {
                return [
                    'name' => $selection->name,
                    'price' => $selection->price,
                    'type' => $selection->type,
                ];
            })->toArray();

            $zap_array = [
                'Menu Title' => $menusection->name,
                'Menu Special Request' => $menusection->special_request,
                'Menu Items' => $selectionData,
                'Request Price' => $menusection->request_price,
                'Total Price' => $menusection->total,
            ];

            $action = 'New Menu Selection';
            $module = 'CateringManagement';
            SendZap::SendZapCall($module, $zap_array, $action);
        }
    }
}
