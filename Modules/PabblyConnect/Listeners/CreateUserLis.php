<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\CreateUser;
use App\Models\WorkSpace;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateUserLis
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
        if(module_is_active('PabblyConnect')){
            $user = $event->user;

            if(Auth::user()->type != "super admin"){

                $workspace = WorkSpace::where('id',$user->active_workspace)->first();
                $active_workspace = $workspace->name;
            }

            $action = 'Create User';
            $module = 'general';

            $pabbly_array = array(
                "name"             => $user['name'],
                "email"            => $user['email'],
                "type"             => $user['type'],
                "lang"             => $user['lang'],
                "active_workspace" => isset($active_workspace) ? $active_workspace : '',
            );

            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}