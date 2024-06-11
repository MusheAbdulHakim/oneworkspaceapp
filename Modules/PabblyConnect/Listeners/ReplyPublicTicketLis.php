<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SupportTicket\Entities\Ticket;
use Modules\SupportTicket\Events\ReplyPublicTicket;

class ReplyPublicTicketLis
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
    public function handle(ReplyPublicTicket $event)
    {
        $ticket = $event->conversion;
        $ticket = Ticket::where('id', '=', $ticket->ticket_id)->first();
        
        if (module_is_active('PabblyConnect', $ticket->created_by)) {
            
            $action = 'New Ticket Reply';
            $module = 'SupportTicket';
            
            $company_id = $ticket->created_by;
            $workspace_id = $ticket->workspace_id;
            
            $pabbly_array = array(
                "ticket_id"   => $ticket['ticket_id'],
                "name"        => $ticket['name'],
                "email"       => $ticket['email'],
                "status"      => $ticket['status'],
                "subject"     => $ticket['subject'],
                "description" => strip_tags($ticket['description']),
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action, $workspace_id);
        }
    }
}