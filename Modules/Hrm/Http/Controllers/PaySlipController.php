<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Account\Entities\BankAccount;
use Modules\Hrm\Entities\Allowance;
use Modules\Hrm\Entities\Commission;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Loan;
use Modules\Hrm\Entities\OtherPayment;
use Modules\Hrm\Entities\Overtime;
use Modules\Hrm\Entities\PaySlip;
use Modules\Hrm\Entities\Resignation;
use Modules\Hrm\Entities\SaturationDeduction;
use Modules\Hrm\Entities\Termination;
use Modules\Hrm\Events\CreateMonthlyPayslip;
use Modules\Hrm\Events\CreatePaymentMonthlyPayslip;
use Modules\Hrm\Events\DestroyMonthlyPayslip;
use Modules\Hrm\Events\PayslipSend;
use Modules\Hrm\Events\UpdateMonthlyPayslip;

class PaySlipController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('setsalary pay slip manage') || !in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            for ($i = 0; $i <= 10; $i++) {
                $data = date("Y", strtotime('-1 years' . " +$i years"));
                $year[$data] = $data;
            }


            for ($i = 0; $i <= 15; $i++) {
                $data = date('Y', strtotime('-5 years' . " +$i years"));
                $years[$data] = $data;
            }

            $month = [
                '01' => 'JAN',
                '02' => 'FEB',
                '03' => 'MAR',
                '04' => 'APR',
                '05' => 'MAY',
                '06' => 'JUN',
                '07' => 'JUL',
                '08' => 'AUG',
                '09' => 'SEP',
                '10' => 'OCT',
                '11' => 'NOV',
                '12' => 'DEC',
            ];
            return view('hrm::payslip.index', compact('month', 'year', 'years'));
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
        $validator = \Validator::make(
            $request->all(),
            [
                'month' => 'required',
                'year' => 'required',

            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $month = $request->month;
        $year  = $request->year;

        $formate_month_year = $year . '-' . $month;
        $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where('workspace', getActiveWorkSpace())->pluck('employee_id');
        $payslip_employee   = Employee::where('workspace', getActiveWorkSpace())->where('company_doj', '<=', date($year . '-' . $month . '-t'))->count();

        if ($payslip_employee > count($validatePaysilp)) {

            $employees = Employee::where('workspace', getActiveWorkSpace())->where('company_doj', '<=', date($year . '-' . $month . '-t'))->whereNotIn('id', $validatePaysilp)->get();
            $employeesSalary = Employee::where('created_by', creatorId())->whereNull('salary')->first();
            if (!empty($employeesSalary)) {
                return redirect()->route('payslip.index')->with('error', __('Please set employee salary.'));
            }

            foreach ($employees as $employee) {

                $terminationDate = Termination::where('employee_id', $employee->id)
                    ->whereDate('termination_date', '<=', Carbon::create($year, $month)->endOfMonth())
                    ->exists();

                $resignationDate = Resignation::where('employee_id', $employee->id)
                    ->whereDate('resignation_date', '<=', Carbon::create($year, $month)->endOfMonth())
                    ->exists();

                if ($terminationDate || $resignationDate) {
                    continue;
                }

                $payslipEmployee                       = new PaySlip();
                $payslipEmployee->employee_id          = $employee->id;
                $payslipEmployee->net_payble           = $employee->get_net_salary();
                $payslipEmployee->salary_month         = $formate_month_year;
                $payslipEmployee->status               = 0;
                $payslipEmployee->basic_salary         = !empty($employee->salary) ? $employee->salary : 0;
                $payslipEmployee->allowance            = Employee::allowance($employee->id);
                $payslipEmployee->commission           = Employee::commission($employee->id);
                $payslipEmployee->loan                 = Employee::loan($employee->id);
                $payslipEmployee->saturation_deduction = Employee::saturation_deduction($employee->id);
                $payslipEmployee->other_payment        = Employee::other_payment($employee->id);
                $payslipEmployee->overtime             = Employee::overtime($employee->id);
                $payslipEmployee->company_contribution = Employee::companycontribution($employee->id);
                $payslipEmployee->workspace            = getActiveWorkSpace();
                $payslipEmployee->created_by           = creatorId();
                $payslip = PaySlip::where('employee_id', $payslipEmployee->employee_id)->where('salary_month', $formate_month_year)->where('workspace', getActiveWorkSpace())->first();
                if (empty($payslip)) {
                    $payslipEmployee->save();
                }

                if (!empty($payslipEmployee)) {
                    $payslipEmployee->tax_bracket = Employee::TaxBracket($payslipEmployee->id, $employee->id, $formate_month_year);
                    $payslipEmployee->save();
                }


                event(new CreateMonthlyPayslip($request, $payslipEmployee));
            }

            return redirect()->route('payslip.index')->with('success', __('Payslip successfully created.'));
        } else {
            return redirect()->route('payslip.index')->with('error', __('Payslip Already created.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('hrm::show');
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
        $payslip = PaySlip::find($id);
        event(new DestroyMonthlyPayslip($payslip));
        $payslip->delete();

        return true;
    }

    public function search_json(Request $request)
    {
        $formate_month_year = $request->datePicker;
        $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where('workspace', getActiveWorkSpace())->get()->toarray();

        $data = [];
        if (empty($validatePaysilp)) {
            return $data;
        } else {
            $paylip_employee = PaySlip::select(
                [
                    'employees.id',
                    'employees.employee_id',
                    'employees.name',
                    'payslip_types.name as payroll_type',
                    'pay_slips.basic_salary',
                    'pay_slips.net_payble',
                    'pay_slips.id as pay_slip_id',
                    'pay_slips.status',
                    'employees.user_id',
                ]
            )->leftjoin(
                'employees',
                function ($join) use ($formate_month_year) {
                    $join->on('employees.id', '=', 'pay_slips.employee_id');
                    $join->on('pay_slips.salary_month', '=', \DB::raw("'" . $formate_month_year . "'"));
                    $join->leftjoin('payslip_types', 'payslip_types.id', '=', 'employees.salary_type');
                }
            )->where('employees.workspace', getActiveWorkSpace())->get();

            foreach ($paylip_employee as $employee) {
                if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                    if (Auth::user()->id == $employee->user_id) {
                        $tmp   = [];
                        $tmp[] = $employee->id;
                        $tmp[] = Employee::employeeIdFormat($employee->employee_id);
                        $tmp[] = $employee->name;
                        $tmp[] = $employee->payroll_type;
                        $tmp[] = !empty($employee->basic_salary) ? currency_format($employee->basic_salary) : '-';
                        $tmp[] = !empty($employee->net_payble) ? currency_format($employee->net_payble) : '-';
                        if ($employee->status == 1) {
                            $tmp[] = 'paid';
                        } else {
                            $tmp[] = 'unpaid';
                        }
                        $tmp[]  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                        $tmp['url']  = route('employee.show', Crypt::encrypt($employee->user_id));
                        $data[] = $tmp;
                        return $data;
                    }
                } else {
                    $tmp   = [];
                    $tmp[] = $employee->id;
                    $tmp[] = Employee::employeeIdFormat($employee->employee_id);
                    $tmp[] = $employee->name;
                    $tmp[] = $employee->payroll_type;
                    $tmp[] = !empty($employee->basic_salary) ? currency_format($employee->basic_salary) : '-';
                    $tmp[] = !empty($employee->net_payble) ? currency_format($employee->net_payble) : '-';
                    if ($employee->status == 1) {
                        $tmp[] = 'Paid';
                    } else {
                        $tmp[] = 'UnPaid';
                    }
                    $tmp[]  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                    $tmp['url']  = route('employee.show', Crypt::encrypt($employee->user_id));
                    $data[] = $tmp;
                }
            }
            return $data;
        }
    }

    public function paysalary($id, $date)
    {
        $employeePayslip = PaySlip::where('employee_id', '=', $id)->where('workspace', getActiveWorkSpace())->where('salary_month', '=', $date)->first();
        $get_employee = Employee::where('id', $id)->where('created_by', creatorId())->first();
        if (module_is_active('Account')) {
            $get_account = BankAccount::where('id', $get_employee->account_type)->where('created_by', creatorId())->first();
            if (isset($get_account) && $get_account->opening_balance <= 0) {
                return redirect()->route('payslip.index')->with('error', __('Account balance is low.'));
            }
            $opening_balance = !empty($get_account->opening_balance) ? $get_account->opening_balance : 0;
            $net_salary = !empty($employeePayslip->net_payble) ? $employeePayslip->net_payble : 0;
        }
        if (!empty($employeePayslip)) {
            $employeePayslip->status = 1;
            $employeePayslip->save();

            if (module_is_active('Account')) {
                $total_balance = $opening_balance - $net_salary;
                $get_account->opening_balance = $total_balance;
                $get_account->save();
            }

            event(new CreatePaymentMonthlyPayslip($employeePayslip));
            return redirect()->route('payslip.index')->with('success', __('Payslip Payment successfully.'));
        } else {
            return redirect()->route('payslip.index')->with('error', __('Payslip Payment failed.'));
        }
    }

    public function pdf($id, $month)
    {
        $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->where('workspace', getActiveWorkSpace())->first();
        $employee = Employee::find($payslip->employee_id);
        $payslipDetail = Employee::employeePayslipDetail($id, $month);
        return view('hrm::payslip.pdf', compact('payslip', 'employee', 'payslipDetail'));
    }

    public function payslipPdf($id)
    {
        $payslipId = Crypt::decrypt($id);

        $payslip  = PaySlip::where('id', $payslipId)->first();
        if (!empty($payslip)) {
            $employee = Employee::find($payslip->employee_id);

            $payslipDetail = Employee::employeePayslipDetail($payslip->employee_id, $payslip->salary_month);

            return view('hrm::payslip.payslipPdf', compact('payslip', 'employee', 'payslipDetail'));
        } else {
            return redirect()->route('payslip.index')->with('error', __('Payslip not found!.'));
        }
    }

    public function send($id, $month)
    {
        $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->where('workspace', getActiveWorkSpace())->first();
        $employee = Employee::find($payslip->employee_id);
        $User     = User::where('id', $employee->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();

        $payslip->name  = $User->name;
        $payslip->email = $User->email;

        $payslipId    = Crypt::encrypt($payslip->id);
        $payslip->url = route('payslip.payslipPdf', $payslipId);

        event(new PayslipSend($id, $month, $payslip));
        $company_settings = getCompanyAllSetting();
        if (!empty($company_settings['New Payroll']) && $company_settings['New Payroll']  == true) {
            $uArr = [
                'payslip_email' => $payslip->email,
                'name'  => $payslip->name,
                'url' => $payslip->url,
                'salary_month' => $payslip->salary_month,
            ];
            try {
                $resp = EmailTemplate::sendEmailTemplate('New Payroll', [$payslip->email], $uArr);
            } catch (\Exception $e) {
                $resp['error'] = $e->getMessage();
            }
            return redirect()->back()->with('success', __('Payslip successfully sent.')  . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }

        return redirect()->back()->with('error', __('Please enable email notification! "New Payroll"'));
    }

    public function editEmployee($paySlip)
    {
        $payslip = PaySlip::find($paySlip);

        return view('hrm::payslip.salaryEdit', compact('payslip'));
    }

    public function updateEmployee(Request $request, $id)
    {
        $payslipEmployee                       = PaySlip::find($request->payslip_id);

        if (isset($request->allowance) && !empty($request->allowance)) {
            $allowances   = $request->allowance;
            $allowanceIds = $request->allowance_id;
            foreach ($allowances as $k => $allownace) {
                $allowanceData         = Allowance::find($allowanceIds[$k]);
                $allowanceData->amount = $allownace;
                $allowanceData->save();
            }
        }


        if (isset($request->commission) && !empty($request->commission)) {
            $commissions   = $request->commission;
            $commissionIds = $request->commission_id;
            foreach ($commissions as $k => $commission) {
                $commissionData         = Commission::find($commissionIds[$k]);
                $commissionData->amount = $commission;
                $commissionData->save();
            }
        }

        if (isset($request->loan) && !empty($request->loan)) {
            $loans   = $request->loan;
            $loanIds = $request->loan_id;
            foreach ($loans as $k => $loan) {
                $loanData         = Loan::find($loanIds[$k]);
                $loanData->amount = $loan;
                $loanData->save();
            }
        }


        if (isset($request->saturation_deductions) && !empty($request->saturation_deductions)) {
            $saturation_deductionss   = $request->saturation_deductions;
            $saturation_deductionsIds = $request->saturation_deductions_id;
            foreach ($saturation_deductionss as $k => $saturation_deductions) {

                $saturation_deductionsData         = SaturationDeduction::find($saturation_deductionsIds[$k]);
                $saturation_deductionsData->amount = $saturation_deductions;
                $saturation_deductionsData->save();
            }
        }


        if (isset($request->other_payment) && !empty($request->other_payment)) {
            $other_payments   = $request->other_payment;
            $other_paymentIds = $request->other_payment_id;
            foreach ($other_payments as $k => $other_payment) {
                $other_paymentData         = OtherPayment::find($other_paymentIds[$k]);
                $other_paymentData->amount = $other_payment;
                $other_paymentData->save();
            }
        }


        if (isset($request->rate) && !empty($request->rate)) {
            $rates   = $request->rate;
            $rateIds = $request->rate_id;
            $hourses = $request->hours;

            foreach ($rates as $k => $rate) {
                $overtime        = Overtime::find($rateIds[$k]);
                $overtime->rate  = $rate;
                $overtime->hours = $hourses[$k];
                $overtime->save();
            }
        }


        $payslipEmployee                       = PaySlip::find($request->payslip_id);
        $payslipEmployee->allowance            = Employee::allowance($payslipEmployee->employee_id);
        $payslipEmployee->commission           = Employee::commission($payslipEmployee->employee_id);
        $payslipEmployee->loan                 = Employee::loan($payslipEmployee->employee_id);
        $payslipEmployee->saturation_deduction = Employee::saturation_deduction($payslipEmployee->employee_id);
        $payslipEmployee->other_payment        = Employee::other_payment($payslipEmployee->employee_id);
        $payslipEmployee->overtime             = Employee::overtime($payslipEmployee->employee_id);
        $payslipEmployee->net_payble           = Employee::find($payslipEmployee->employee_id)->get_net_salary();
        $payslipEmployee->save();
        event(new UpdateMonthlyPayslip($request, $payslipEmployee));
        return redirect()->route('payslip.index')->with('success', __('Employee payroll successfully updated.'));
    }
}
