<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SupportTicket\Entities\TicketCategory;
use Modules\SupportTicket\Events\ReplyTicket;

class ReplyTicketLis
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
    public function handle(ReplyTicket $event)
    {
        $ticket = $event->ticket;
        if (module_is_active('PabblyConnect')) {
            $category = TicketCategory::where('id', $ticket->category)->first();
            // $ticket['category'] = $category->name;

            $action = 'New Ticket Reply';
            $module = 'SupportTicket';

            $pabbly_array = array(
                "ticket_id"   => $ticket['ticket_id'],
                "name"        => $ticket['name'],
                "email"       => $ticket['email'],
                "status"      => $ticket['status'],
                "subject"     => $ticket['subject'],
                "description" => strip_tags($ticket['description']),
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}