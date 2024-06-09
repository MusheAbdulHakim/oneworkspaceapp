<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateAllowanceTax
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $allowancetax;

    public function __construct($request, $allowancetax)
    {
        $this->request = $request;
        $this->allowancetax = $allowancetax;
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
