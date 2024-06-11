<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Events\CreateDeal;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDealLis
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
    public function handle(CreateDeal $event)
    {
        if (module_is_active('PabblyConnect')) {
            $deal = $event->deal;
            $request = $event->request;
            $clients_name = User::whereIN('id', array_filter($request->clients))->get()->pluck('name')->toArray();

            $pipeline = Pipeline::where('id', $deal->pipeline_id)->first();
            $stage = DealStage::where('id', $deal->stage_id)->first();

            $deal->clients     = (count($clients_name) > 0) ? implode(',', $clients_name) : 'Not Found';
            $action = 'New Deal';
            $module = 'Lead';

            $pabbly_array = array(
                "name"   => $deal['name'],
                "price"  => $deal['price'],
                "status" => $deal['status'],
                "phone"  => $deal['phone'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}