<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Events\CreateInquiry;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateInquiryLis
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
    public function handle(CreateInquiry $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $inquiry = $event->inquiry;

            $pabbly_array = [
                'Child First Name' => $inquiry->child_first_name,
                'Child Last Name' => $inquiry->child_last_name,
                'Parent First Name' => $inquiry->parent_first_name,
                'Parent Last Name' => $inquiry->parent_last_name,
                'Parent Contact Number' => $inquiry->contact_number,
                'Child Age' => $inquiry->child_age,
                'Child Date of Birth' => $inquiry->child_dob,
                'Child Gender' => $inquiry->child_gender,
                'Inquiry Date' => $inquiry->date,
                'Message' => $inquiry->message,
            ];

            $action = 'New Inquiry';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
