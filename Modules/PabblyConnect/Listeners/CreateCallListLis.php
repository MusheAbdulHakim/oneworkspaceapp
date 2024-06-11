<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CallHub\Entities\CallhubModule;
use Modules\CallHub\Entities\CallType;
use Modules\CallHub\Events\CreateCallList;
use Modules\Hrm\Entities\AwardType;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\Lead;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCallListLis
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
    public function handle(CreateCallList $event)
    {
        if (module_is_active('PabblyConnect')) {
            $calls = $event->calls;
            $request = $event->request;

            $call_type = CallType::find($calls->call_type);
            $callhub_module = CallhubModule::find($calls->sub_module);
            $pabbly_array = [];

            if ($callhub_module->module == 'Account' && ($callhub_module->submodule == 'Customer' || $callhub_module->submodule == 'Vendor')) {
                $user_id = explode(',', $calls->user_id);
                $users = User::WhereIn('id', $user_id)->get();
                $user_data = [];

                foreach ($users as $user) {
                    $newArray = [
                        'User Name' => $user->name,
                        'User Email' => $user->email,
                        'User Mobile Number' => $user->mobile_no
                    ];
                    $user_data[] = $newArray;
                }
                $pabbly_array = [
                    'Caller' => $calls->caller,
                    'Call Subject' => $calls->subject,
                    'Call Type' => $call_type->name,
                    'Call Direction' => $calls->call_direction,
                    'Call User' => $user_data,
                ];
            } else if ($callhub_module->module == 'Lead') {
                $user = User::whereIn('id', $request->users)->first();

                if ($callhub_module->submodule == 'Lead') {
                    $lead = Lead::whereIn('id', $request->leads)->first();

                    $pabbly_array = [
                        'Lead Title' => $lead->name,
                        'Lead Email' => $lead->email,
                        'Lead Subject' => $lead->subject,
                        'User Name' => $user->name,
                        'User Email' => $user->email,
                        'User Phone Number' => $user->mobile_no,
                        'Caller' => $calls->caller,
                        'Subject' => $calls->subject,
                        'Call Type' => $call_type->name,
                        'Call Direction' => $calls->call_direction,
                    ];
                } else {
                    $deal = Deal::whereIn('id', $request->deals)->first();

                    $pabbly_array = [
                        'Deal Title' => $deal->name,
                        'Deal Price' => $deal->price,
                        'Deal Status' => $deal->status,
                        'User Name' => $user->name,
                        'User Email' => $user->email,
                        'User Phone Number' => $user->mobile_no,
                        'Caller' => $calls->caller,
                        'Subject' => $calls->subject,
                        'Call Type' => $call_type->name,
                        'Call Direction' => $calls->call_direction,
                    ];
                }
            } else if ($callhub_module->module == 'Hrm') {

                if ($callhub_module->submodule == 'Employee') {
                    $users = User::WhereIn('id', $request->users)->get();

                    $user_data = [];

                    foreach ($users as $user) {
                        $newArray = [
                            'User Name' => $user->name,
                            'User Email' => $user->email,
                            'User Mobile Number' => $user->mobile_no
                        ];
                        $user_data[] = $newArray;
                    }

                    $pabbly_array = [
                        'Caller' => $calls->caller,
                        'Call Subject' => $calls->subject,
                        'Call Type' => $call_type->name,
                        'Call Direction' => $calls->call_direction,
                        'Call User' => $user_data,
                    ];
                } else if ($callhub_module->submodule == 'Award') {
                    $user = User::whereIn('id', $request->users)->first();
                    $award_type = AwardType::whereIn('id', $request->award_types)->first();

                    $pabbly_array = [
                        'Caller' => $calls->caller,
                        'Call Subject' => $calls->subject,
                        'Call Type' => $call_type->name,
                        'Call Direction' => $calls->call_direction,
                        'User Name' => $user->name,
                        'User Email' => $user->email,
                        'Award Type' => $award_type->name,
                    ];
                }
            }

            $action = 'New Call List';
            $module = 'CallHub';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
