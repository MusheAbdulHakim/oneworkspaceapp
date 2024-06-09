<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSaturationDeduction
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $saturationdeduction;

    public function __construct($request, $saturationdeduction)
    {
        $this->request = $request;
        $this->saturationdeduction = $saturationdeduction;
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
