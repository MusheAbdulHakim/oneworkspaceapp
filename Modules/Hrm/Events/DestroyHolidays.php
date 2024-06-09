<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyHolidays
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $holiday;

    public function __construct($holiday)
    {
        $this->holiday = $holiday;
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
