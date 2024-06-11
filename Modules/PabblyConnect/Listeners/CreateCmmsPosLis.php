<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Supplier;
use Modules\CMMS\Events\CreateCmmspos;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCmmsPosLis
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
    public function handle(CreateCmmspos $event)
    {
        if (module_is_active('PabblyConnect')) {
            $pos = $event->Pos;
            $request = $event->request;

            // dd($pos, $request);

            $user = User::find($request->user_id);
            $location = Location::find($request->location);
            $supplier = Supplier::find($request->supplier_id);

            $action = 'New Pos';
            $module = 'CMMS';

            $pabbly_array = array(
                "Pos Date"      => $pos['pos_date'],
                "Delivery Date" => $pos['delivery_date'],
                "User Name"     => $user->name,
                "Location"      => $location->name,
                "Supplier"      => $supplier->name,
                "Items"         => $request->items
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}