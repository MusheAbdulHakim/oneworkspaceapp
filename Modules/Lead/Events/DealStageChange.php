<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DealStageChange
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $post;
    public $stage;

    public function __construct($post,$stage)
    {
        $this->post = $post;
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
