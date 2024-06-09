<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPromotion
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $promotion;

    public function __construct($promotion)
    {
        $this->promotion = $promotion;
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
