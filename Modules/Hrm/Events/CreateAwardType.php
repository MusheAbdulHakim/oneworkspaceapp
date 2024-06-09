<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateAwardType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $awardtype;

    public function __construct($request, $awardtype)
    {
        $this->request = $request;
        $this->awardtype = $awardtype;
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
