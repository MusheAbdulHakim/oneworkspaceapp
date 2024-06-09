<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateCompanyPolicy
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $policy;

    public function __construct($request, $policy)
    {
        $this->request = $request;
        $this->policy = $policy;
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
