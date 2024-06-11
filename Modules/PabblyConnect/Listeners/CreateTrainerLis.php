<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Branch;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Training\Events\CreateTrainer;

class CreateTrainerLis
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
    public function handle(CreateTrainer $event)
    {
        if(module_is_active('PabblyConnect')){
            $trainer = $event->trainer;
            $request =$event->request;

            $branch = Branch::where('id',$request->branch)->first();
            
            if(!empty($branch)){

                $trainer->branch     = $branch->name;
            }
            
            $action = 'New Trainer';
            $module = 'Training';
            
            $pabbly_array = array(
                "firstname" => $trainer['firstname'],
                "lastname"  => $trainer['lastname'],
                "email"     => $trainer['email'],
                "contact"   => $trainer['contact'],
                "address"   => $trainer['address'],
                "expertise" => $trainer['expertise'],
            );
            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}