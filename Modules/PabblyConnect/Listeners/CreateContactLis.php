<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VCard\Entities\Business;
use Modules\VCard\Events\CreateContact;

class CreateContactLis
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
    public function handle(CreateContact $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $contact = $event->contact;

            $business = Business::find($contact->business_id);

            $action = 'New Contact';
            $module = 'VCard';
            $pabbly_array = array(
                "Business Title"   => $business->title,
                "Name"             => $contact['name'],
                "Email"            => $contact['email'],
                "Phone"            => $contact['phone'],
                "Message"          => $contact['message'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}