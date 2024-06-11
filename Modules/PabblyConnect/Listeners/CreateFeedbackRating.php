<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Feedback\Entities\TemplateModule;
use Modules\Feedback\Events\CreateRating;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFeedbackRating
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
    public function handle(CreateRating $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $rating = $event->rating;
            
            
            if(!empty($rating->user_id) && $rating->user_id != 0){
                $employee =  User::where('id', $rating->user_id)->first();
            } else {
                $emp_data = json_decode($rating->content);
            }

            $module_data = TemplateModule::find($rating->module_id);

            $user_name = (!empty($rating->user_id) && $rating->user_id != 0) ? $employee->name : $emp_data->name;
            $user_email = (!empty($rating->user_id) && $rating->user_id != 0) ? $employee->email : $emp_data->email;

            $action = 'New Rating';
            $module = 'Feedback';
            $pabbly_array = array(
                "Employee Name"    => $user_name,
                "Employee Email"   => $user_email,
                "Module Title"     => $module_data->module,
                "Sub Module Title" => $module_data->submodule,
                "Rating"           => $rating->rating,
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}