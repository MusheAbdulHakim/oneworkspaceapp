<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
     public $request;
     public $event;
 
     public function __construct($request ,$event)
     {
         $this->request = $request;
         $this->event = $event;
     }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
