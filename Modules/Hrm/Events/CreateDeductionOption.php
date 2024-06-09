<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateDeductionOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $deductionoption;

    public function __construct($request, $deductionoption)
    {
        $this->request = $request;
        $this->deductionoption = $deductionoption;
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
