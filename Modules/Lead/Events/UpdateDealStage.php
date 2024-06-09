<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDealStage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $dealStage;

    public function __construct($request,$dealStage)
    {
        $this->request = $request;
        $this->dealStage = $dealStage;
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
