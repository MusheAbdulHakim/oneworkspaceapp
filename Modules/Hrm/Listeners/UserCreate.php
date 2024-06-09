<?php

namespace Modules\Hrm\Listeners;

use App\Events\CreateUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateUser $event)
    {
        $request = $event->request;
        $user = $event->user;
    }
}
