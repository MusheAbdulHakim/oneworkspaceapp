<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class LeadStageChange
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $post;
    public $lead_stage;

    public function __construct($post,$lead_stage)
    {
        $this->post = $post;
        $this->lead_stage = $lead_stage;
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
