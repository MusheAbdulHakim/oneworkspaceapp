<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CleaningManagement\Entities\CleaningBooking;
use Modules\CleaningManagement\Entities\CleaningInspection;
use Modules\CleaningManagement\Events\CreateCleaningInvoice;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCleaningInvoiceLis
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
    public function handle(CreateCleaningInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $invoice_id = $event->id;
            $invoice = $event->invoice;

            $inspection = CleaningInspection::find($invoice->inspection_id);
            $booking = CleaningBooking::find($inspection->booking_id);

            $pabbly_array = [
                'Customer Name' => $booking->customer_name,
                'Building Type' => $booking->building_type,
                'Customer Address' => $booking->address,
                'Description' => $booking->description,
                'Booking Date' => $booking->booking_date,
                'Cleaning Date' => $booking->cleaning_date,
                'Inspection Status' => $inspection->status == 1 ? 'Cleaned' : 'Dirt',
            ];

            $action = 'New Cleaning Invoice';
            $module = 'CleaningManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
