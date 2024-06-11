<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeautySpaManagement\Entities\BeautyService;
use Modules\BeautySpaManagement\Events\CreateBeautyBooking;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBeautyBookingLis
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
    public function handle(CreateBeautyBooking $event)
    {
        if (module_is_active('PabblyConnect')) {
            $beautybooking = $event->beautybooking;

            $service = BeautyService::find($beautybooking->service);

            $pabbly_array = [
                'Name' => $beautybooking->name,
                'Service Title' => $service->name,
                'Service Price' => $service->price,
                'Service Time' => $service->time,
                'Booking Date' => $beautybooking->date,
                'Customer Mobile Number' => $beautybooking->number,
                'Customer Email' => $beautybooking->email
            ];

            $action = 'New Beauty Booking';
            $module = 'BeautySpaManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
