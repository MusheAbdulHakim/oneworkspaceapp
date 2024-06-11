<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Events\CreateComponent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateComponentLis
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
    public function handle(CreateComponent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $component = $event->components;
            $request = $event->request;

            $location = Location::find($component->location_id);

            $action = 'New Component';
            $module = 'CMMS';

            $pabbly_array = array(
                "Component"         => $component['name'],
                "SKU"               => $component['sku'],
                "Location"          => $location['name'],
                "Address"           => $location['address'],
                "Component Tag"     => $request['Component_Tag']['Component_Tag'],
                "Category"          => $request['Category']['Category'],
                "Assigned Date"     => $request['Assigned_Date']['Assigned Date'],
                "Link"              => $request['Link']['Link'],
                "Model"             => $request['Model']['Model'],
                "Brand"             => $request['Brand']['Brand'],
                "Operating Hours"   => $request['Operating_Hours']['Operating Hours'],
                "Original Cost"     => $request['Original_Cost']['Original Cost'],
                "Purchase Cost"     => $request['Purchase_Cost']['Purchase Cost'],
                "Serial Number"     => $request['Serial_Number']['Serial Number'],
                "Service Contact"   => $request['Service_Contact']['Service Contact'],
                "Warranty Exp Date" => $request['Warranty_Exp_Date']['Warranty Exp Date'],
                "Description"       => $request['Description']['Description'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}