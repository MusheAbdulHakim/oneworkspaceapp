<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Branch;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Recruitment\Entities\JobCategory;
use Modules\Recruitment\Events\CreateJob;

class CreateJobLis
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
    public function handle(CreateJob $event)
    {
        if (module_is_active('PabblyConnect')) {
            $job = $event->job;
            if ($job->category) {
                $categories = JobCategory::where('id', $job->category)->first();
            }
            if ($job->branch) {
                $branch = Branch::where('id', $job->branch)->first();
            }
            $action = 'New Job';
            $module = 'Recruitment';
            $pabbly_array = array(
                "title"       => $job['title'],
                "Branch"      => $branch->name,
                "category"    => $categories->title,
                "skill"       => $job['skill'],
                "position"    => $job['position'],
                "status"      => $job['status'],
                "start_date"  => $job['start_date'],
                "end_date"    => $job['end_date'],
                "description" => strip_tags($job['description']),
                "requirement" => strip_tags($job['requirement']),
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}