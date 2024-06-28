<?php

namespace Modules\GoHighLevel\Listeners;

use App\Events\CreateUser;
use App\Jobs\ProcessGoHighLevelUser;
use Modules\GoHighLevel\Events\GoHighLevelEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GoHighLevel\Helper\GohighlevelHelper;
use Twilio\Rest\Chat\V1\Service\CreateUserOptions;

class GoHighLevelListener
{
    private $ghl;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ghl = new GohighlevelHelper();
    }

    /**
     * Handle the event.
     *
     */
    public function handle(CreateUser $event)
    {
        $user = $event->user;
        $helper = new GohighlevelHelper();
        $helper->createSubAccount($user, $event->request);

    }
}
