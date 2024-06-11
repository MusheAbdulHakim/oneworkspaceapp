<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SupportTicket\Entities\TicketCategory;
use Modules\SupportTicket\Events\CreatePublicTicket;

class CreatePublicTicketLis
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
    public function handle(CreatePublicTicket $event)
    {
        $ticket = $event->ticket;

        if (module_is_active('PabblyConnect', $ticket->created_by)) {
            $category = TicketCategory::where('id', $ticket->category)->first();
            $ticket->category = !empty($category) ? $category->name : '';
            $action = 'New Ticket';
            $module = 'SupportTicket';
            $company_id = $ticket->created_by;
            $workspace_id = $ticket->workspace_id;
            $pabbly_array = array(
                "name"        => $ticket['name'],
                "email"       => $ticket['email'],
                "status"      => $ticket['status'],
                "subject"     => $ticket['subject'],
                "description" => strip_tags($ticket['description']),
                "ticket_id"   => $ticket['ticket_id'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action, $workspace_id);
        }
    }
}