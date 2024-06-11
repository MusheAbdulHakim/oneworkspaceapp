<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Events\CreateSchoolEmployee;

class CreateSchoolEmployeeLis
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
    public function handle(CreateSchoolEmployee $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $employee = $event->employee;

            $branch = Branch::find($employee->branch_id);
            $department = Department::find($employee->department_id);
            $designation = Designation::find($employee->designation_id);

            $pabbly_array = [
                'Employee Name' => $employee->name,
                'Date of Birth' => $employee->dob,
                'Gender' => $employee->gender,
                'Phone Number' => $employee->phone,
                'Employee Address' => $employee->address,
                'Employee Email' => $employee->email,
                'Employee Branch' => $branch->name,
                'Employee Department' => $department->name,
                'Employee Designation' => $designation->name,
                'Employee Date of Join' => $employee->company_doj,
                'Employee Account Holder Name' => $employee->account_holder_name,
                'Employee Account Number' => $employee->account_number,
                'Employee Bank Name' => $employee->bank_name,
                'Employee Bank Identifier Name' => $employee->bank_identifier_code,
                'Branch Location' => $employee->branch_location,
                'Tax Payer Name' => $employee->tax_payer_id
            ];

            $action = 'New Employee';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
