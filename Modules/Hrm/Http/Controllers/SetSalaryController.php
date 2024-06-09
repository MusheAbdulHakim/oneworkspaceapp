<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\BankAccount;
use Modules\Hrm\Entities\Allowance;
use Modules\Hrm\Entities\AllowanceOption;
use Modules\Hrm\Entities\Commission;
use Modules\Hrm\Entities\CompanyContribution;
use Modules\Hrm\Entities\DeductionOption;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Loan;
use Modules\Hrm\Entities\LoanOption;
use Modules\Hrm\Entities\OtherPayment;
use Modules\Hrm\Entities\Overtime;
use Modules\Hrm\Entities\PayslipType;
use Modules\Hrm\Entities\SaturationDeduction;
use Modules\Hrm\Events\UpdateEmployeeSalary;

class SetSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('setsalary manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $employees = Employee::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
            } else {
                $employees = Employee::where('workspace', getActiveWorkSpace())->with('salaryType')->get();
            }
            return view('hrm::setsalary.index', compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hrm::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $payslip_type      = PayslipType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
        $allowance_options = AllowanceOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
        $loan_options      = LoanOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
        $deduction_options = DeductionOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $currentEmployee      = Employee::where('user_id', '=', \Auth::user()->id)->where('workspace', getActiveWorkSpace())->first();
            $allowances           = Allowance::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $commissions          = Commission::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $loans                = Loan::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $saturationdeductions = SaturationDeduction::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $otherpayments        = OtherPayment::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $companycontributions = CompanyContribution::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $overtimes            = Overtime::where('employee_id', $currentEmployee->id)->where('workspace', getActiveWorkSpace())->get();
            $employee             = Employee::where('user_id', '=', \Auth::user()->id)->where('workspace', getActiveWorkSpace())->first();

            foreach ($allowances as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($commissions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($loans as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($saturationdeductions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($otherpayments as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }
            
            foreach ($companycontributions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }
            return view('hrm::setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'companycontributions'));
        } else {
            $allowances           = Allowance::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $commissions          = Commission::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $loans                = Loan::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $saturationdeductions = SaturationDeduction::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $otherpayments        = OtherPayment::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $companycontributions = CompanyContribution::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $overtimes            = Overtime::where('employee_id', $id)->where('workspace', getActiveWorkSpace())->get();
            $employee             = Employee::find($id);

            foreach ($allowances as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($commissions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($loans as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($saturationdeductions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ($otherpayments as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }
            
            foreach ($companycontributions as  $value) {
                if ($value->type == 'percentage') {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            return view('hrm::setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'companycontributions'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hrm::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function employeeBasicSalary($id)
    {
        $payslip_type = PayslipType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
        $employee     = Employee::find($id);
        $bankAccount = [];
        if (module_is_active('Account')) {
            $bankAccount = BankAccount::select('*', DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
        }
        return view('hrm::setsalary.basic_salary', compact('employee', 'payslip_type', 'bankAccount'));
    }

    public function employeeUpdateSalary(Request $request, $id)
    {
        $rules = [
            'salary_type' => 'required',
            'salary' => ['required','numeric','min:0'],
        ];
        if (module_is_active('Account')) {
            $rules['account_type'] = 'required';
        }
        $validator = \Validator::make(
            $request->all(),
            $rules
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $employee = Employee::findOrFail($id);
        $input    = $request->all();
        $employee->fill($input)->save();

        event(new UpdateEmployeeSalary($request, $employee));

        return redirect()->back()->with('success', 'Employee Salary Updated.');
    }
}
