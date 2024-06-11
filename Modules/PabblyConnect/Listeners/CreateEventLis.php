<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\CreateEvent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateEventLis
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
    public function handle(CreateEvent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $event = $event->event;
            if (in_array('0', $request->department_id)) {
                $department_name = 'All Departments';
            } else {
                $department_name = 'Not Found';
                $department = Department::whereIn('id', $request->department_id)->get()->pluck('name')->toArray();
                if (count($department) > 0) {
                    $department_name = implode(',', $department);
                }
            }
            if (in_array('0', $request->employee_id)) {
                $employee_name = 'All Employees';
            } else {
                $employee_name = 'Not Found';
                $employee = Employee::whereIn('id', $request->employee_id)->get()->pluck('name')->toArray();
                if (count($employee) > 0) {
                    $employee_name = implode(',', $employee);
                }
            }
            if ($request->branch_id == '0') {
                $branch_name = 'All Branch';
            } else {
                $branch = Branch::where('id', $request->branch_id)->first();
                $branch_name = $branch->name;
            }
            $event->branch_name = $branch_name;
            $event->branch_name = $branch_name;
            $event->employee_name = $employee_name;

            $action = 'New Event';
            $module = 'Hrm';

            $pabbly_array = array(
                "branch"      => $branch_name,
                "department"  => $department_name,
                "employee"    => $employee_name,
                "title"       => $event['title'],
                "start_date"  => $event['start_date'],
                "end_date"    => $event['end_date'],
                "description" => $event['description'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}