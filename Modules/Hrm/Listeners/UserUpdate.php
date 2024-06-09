<?php

namespace Modules\Hrm\Listeners;

use App\Events\UpdateUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Employee;

class UserUpdate
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
    public function handle(UpdateUser $event)
    {
        $request = $event->request;
        $user = $event->user;
        $employee     = Employee::where('user_id',$user->id)->first();
        if(!empty($employee))
        {
            $employee->name = $request->name;
            $employee->save();
        }
    }
}
