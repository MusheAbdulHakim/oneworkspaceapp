<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Recruitment\Entities\Job;
use Modules\Recruitment\Entities\JobStage;
use Modules\Recruitment\Events\CreateJobApplication;

class CreateJobApplicationLis
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
    public function handle(CreateJobApplication $event)
    {
        if (module_is_active('PabblyConnect')) {
            $job = $event->job;
            if ($job->stage) {
                $stage = JobStage::where('id', $job->stage)->first();
            }
            if ($job->job) {
                $jobs = Job::where('id', $job->job)->first();
            }
            unset($job->profile, $job->resume);
            $action = 'New Job Application';
            $module = 'Recruitment';
            $pabbly_array = array(
                "name"    => $job['name'],
                "email"   => $job['email'],
                "phone"   => $job['phone'],
                "profile" => $job['profile'],
                "resume"  => $job['resume'],
                "dob"     => $job['dob'],
                "gender"  => $job['gender'],
                "country" => $job['country'],
                "city"    => $job['city'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action, $job->workspace);
        }
    }
}