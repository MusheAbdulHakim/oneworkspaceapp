<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDealStage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $stage;

    public function __construct($stage)
    {
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
