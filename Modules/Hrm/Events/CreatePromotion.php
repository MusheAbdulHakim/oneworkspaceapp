<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreatePromotion
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $promotion;

    public function __construct($request, $promotion)
    {
        $this->request = $request;
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
