<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\License;
use Modules\Fleet\Events\CreateDriver;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDriverLis
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
    public function handle(CreateDriver $event)
    {
        if(module_is_active('PabblyConnect')){
            $driver = $event->driver;
            $request = $event->request;

            $license = License::find($driver->lincese_type);

            $action = 'New Driver';
            $module = 'Fleet';
            $pabbly_array = array(
                "Driver Name"                => $driver['name'],
                "Driver Email"               => $driver['email'],
                "Driver Phone Number"        => $driver['phone'],
                "Driver Date of Birth"       => $driver['dob'],
                "Driver Join Date"           => $driver['join_date'],
                "Driver Licence Number"      => $driver['lincese_number'],
                "Driver Licence Type"        => $license->name,
                "Driver Licence Expire Date" => $driver['expiry_date'],
                "Driver Working Time"        => $driver['Working_time'],
                "Driver Address"             => $driver['address'],
            );
            
            PabblySend::SendPabblyCall($module ,$pabbly_array ,$action);
        }
    }
}