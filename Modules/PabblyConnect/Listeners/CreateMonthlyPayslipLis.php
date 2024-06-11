<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Events\CreateMonthlyPayslip;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMonthlyPayslipLis
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
    public function handle(CreateMonthlyPayslip $event)
    {
        if (module_is_active('PabblyConnect')) {
            $payslipEmployee = $event->payslipEmployee;
            $employee = \Modules\Hrm\Entities\Employee::where('id', $payslipEmployee->employee_id)->first();
            if (!empty($employee)) {
                // $payslipEmployee->employee_id = $employee->name;
            }

            $action = 'New Monthly Payslip';
            $module = 'Hrm';
            $pabbly_array = array(
                "employee Name"        => $employee->name,
                "net_payble"           => $payslipEmployee['net_payble'],
                "salary_month"         => $payslipEmployee['salary_month'],
                "basic_salary"         => $payslipEmployee['basic_salary'],
                "allowance"            => $payslipEmployee['allowance'],
                "commission"           => $payslipEmployee['commission'],
                "loan"                 => $payslipEmployee['loan'],
                "saturation_deduction" => $payslipEmployee['saturation_deduction'],
                "other_payment"        => $payslipEmployee['other_payment'],
                "overtime"             => $payslipEmployee['overtime'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}