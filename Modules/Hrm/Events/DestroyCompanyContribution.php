<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCompanyContribution
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $companycontribution;

    public function __construct($companycontribution)
    {
        $this->companycontribution = $companycontribution;
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
