<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateAward
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
     public $request;
     public $award;
 
     public function __construct($request ,$award)
     {
         $this->request = $request;
         $this->award = $award;
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
