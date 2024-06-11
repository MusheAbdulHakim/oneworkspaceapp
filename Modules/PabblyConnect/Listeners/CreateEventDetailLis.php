<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CateringManagement\Entities\CateringCustomer;
use Modules\CateringManagement\Entities\MenuSelection;
use Modules\CateringManagement\Events\CreateEventDetail;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateEventDetailLis
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
    public function handle(CreateEventDetail $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $EventDetail = $event->EventDetail;

            $customer = CateringCustomer::find($EventDetail->customer_id);
            $menu_selections = MenuSelection::find($EventDetail->menu_selction_id);

            $pabbly_array = [
                'Event Location' => $EventDetail->event_location,
                'Venue Requirements' => $EventDetail->venue_requirements,
                'Delivery Pickup Option' => $EventDetail->delivery_pickup_option,
                'Additional Services' => $EventDetail->additional_services,
                'Services Price' => $EventDetail->services_price,
                'Customer Name' => $customer->name,
                'Customer Email' => $customer->email,
                'Customer Phone Number' => $customer->phone_no,
                'Menu Selection' => $menu_selections->name,
                'Menu Special Request' => $menu_selections->special_request,
                'Menu Price' => $menu_selections->request_price,
                'Total Amount' => $menu_selections->total,
            ];

            $action = 'New Event Details';
            $module = 'CateringManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
