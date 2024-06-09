<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class CreateDealStage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $stage;

    public function __construct($request,$stage)
    {
        $this->request = $request;
        $this->stage = $stage;
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
