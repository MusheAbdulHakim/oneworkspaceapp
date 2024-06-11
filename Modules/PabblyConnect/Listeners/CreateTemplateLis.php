<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Feedback\Entities\TemplateModule;
use Modules\Feedback\Events\CreateTemplate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateTemplateLis
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
    public function handle(CreateTemplate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $templates = $event->templates;

            $module_data = TemplateModule::find($templates->module);

            $action = 'New Template';
            $module = 'Feedback';
            
            $pabbly_array = array(
                "Module Title"     => $module_data->module,
                "Sub Module Title" => $module_data->submodule,
                "Subject"          => $templates->subject,
                "From"             => $templates->from,
                "Content"          => strip_tags($templates->content),
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}