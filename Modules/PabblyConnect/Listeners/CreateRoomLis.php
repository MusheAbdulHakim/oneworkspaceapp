<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateRoom;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateRoomLis
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
    public function handle(CreateRoom $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $room = $event->room;

            $pabbly_array = [
                'Room Type' => $room->room_type,
                'Short Description' => strip_tags($room->short_description),
                'Adults' => $room->adults,
                'Childrens' => $room->children,
                'Total Rooms' => $room->total_room,
                'Base Price' => $room->base_price,
                'Final Price' => $room->final_price,
                'Description' => strip_tags($room->description)
            ];

            $action = 'New Room';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
