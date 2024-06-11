<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Events\CreateCompanyPolicy;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCompanyPolicyLis
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
    public function handle(CreateCompanyPolicy $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $policy = $event->policy;

            $branch = \Modules\Hrm\Entities\Branch::where('id', $request->branch)->first();
            if (!empty($branch)) {
                $policy->branch = $branch->name;
            }
            unset($policy->attachment);
            $action = 'New Company Policy';
            $module = 'Hrm';

            $pabbly_array = array(
                "title"       => $policy['title'],
                "description" => $policy['description'],
                "branch"      => $branch->name,
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}