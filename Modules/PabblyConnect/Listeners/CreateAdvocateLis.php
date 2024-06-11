<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Events\CreateAdvocate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAdvocateLis
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
    public function handle(CreateAdvocate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $advocate = $event->advocate;

            $pabbly_array = [
                'Advocate Name' => $request->name,
                'Advocate Email' => $request->email,
                'Advocate Phone Number' => $request->phone_number,
                'Advocate Age' => $advocate->age,
                'Company Name' => $advocate->company_name,
                'Bank Details' => $advocate->bank_details,
                'Office Address Line 1' => $advocate->ofc_address_line_1,
                'Office Address Line 2' => $advocate->ofc_address_line_2,
                'Office Country' => $advocate->ofc_country,
                'Office State' => $advocate->ofc_state,
                'Office City' => $advocate->ofc_city,
                'Office Zip Code' => $advocate->ofc_zip_code,
                'Home Address Line 1' => $advocate->home_address_line_1,
                'Home Address Line 2' => $advocate->home_address_line_2,
                'Home Country' => $advocate->home_country,
                'Home State' => $advocate->home_state,
                'Home City' => $advocate->home_city,
                'Home Zip Code' => $advocate->home_zip_code,
            ];

            $action = 'New Advocate';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
