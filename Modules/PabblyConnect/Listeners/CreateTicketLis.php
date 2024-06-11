<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SupportTicket\Entities\TicketCategory;
use Modules\SupportTicket\Events\CreateTicket;

class CreateTicketLis
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
    public function handle(CreateTicket $event)
    {
        $ticket = $event->ticket;
        if (module_is_active('PabblyConnect')) {
            $category = TicketCategory::where('id', $ticket->category)->first();

            $action = 'New Ticket';
            $module = 'SupportTicket';

            $pabbly_array = array(
                "name" => $ticket['name'],
                "email" => $ticket['email'],
                "Category" => $category->name,
                "status" => $ticket['status'],
                "subject" => $ticket['subject'],
                "description" => strip_tags($ticket['description']),
                "ticket_id" => $ticket['ticket_id'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}