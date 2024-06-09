<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCompanyPolicy
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $companyPolicy;

    public function __construct($companyPolicy)
    {
        $this->companyPolicy = $companyPolicy;
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
