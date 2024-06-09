<?php

namespace Modules\Hrm\Entities;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\BankAccount;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'dob',
        'gender',
        'phone',
        'address',
        'email',
        'password',
        'passport_country',
        'passport',
        'location_type',
        'country',
        'state',
        'city',
        'zipcode',
        'employee_id',
        'biometric_emp_id',
        'branch_id',
        'department_id',
        'designation_id',
        'company_doj',
        'documents',
        'account_holder_name',
        'account_number',
        'bank_name',
        'bank_identifier_code',
        'branch_location',
        'tax_payer_id',
        'salary_type',
        'account_type',
        'hours_per_day',
        'annual_salary',
        'days_per_week',
        'fixed_salary',
        'hours_per_month',
        'rate_per_day',
        'days_per_month',
        'rate_per_hour',
        'payment_requires_work_advice',
        'salary',
        'is_active',
        'workspace',
        'created_by',
    ];

    public static $location_type = [
        '' => 'Select Location Type',
        'residential'  => 'Residential',
        'postal'       => 'Postal',
        'work_address' => 'Work Address',
    ];

    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\EmployeeFactory::new();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public static function Branchs($id)
    {
        return Branch::where('id', $id)->first();
    }
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    public static function Departments($id)
    {
        return Department::where('id', $id)->first();
    }
    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }
    public static function Designations($id)
    {
        return Designation::where('id', $id)->first();
    }

    public static function employeeIdFormat($number)
    {
        $company_settings = getCompanyAllSetting();
        $employee_prefix = !empty($company_settings['employee_prefix']) ? $company_settings['employee_prefix'] : '#EMP000';
        return $employee_prefix . sprintf("%05d", $number);
    }
    public static function present_status($employee_id, $data)
    {
        return Attendance::where('employee_id', $employee_id)->where('date', $data)->first();
    }
    public function documents()
    {
        return $this->hasOne(EmployeeDocument::class, 'employee_id', 'employee_id');
    }
    public static function getEmployee($employee)
    {
        $employee = User::where('id', '=', $employee)->first();
        $employee = !empty($employee) ? $employee : null;
        return $employee;
    }
    public static function GetEmployeeByEmp($employee)
    {
        $employee = Employee::where('id', '=', $employee)->first();
        $employee = !empty($employee) ? $employee : null;
        return $employee;
    }
    public function salary_type()
    {
        return $this->hasOne(PayslipType::class, 'id', 'salary_type')->pluck('name')->first();
    }

    public function account_type()
    {
        return $this->hasOne(BankAccount::class, 'id', 'account_type')->pluck('bank_name', 'holder_name')->first();
    }

    public function salaryType()
    {
        return $this->hasOne(PayslipType::class, 'id', 'salary_type');
    }

    public function accountType()
    {
        return $this->hasOne(BankAccount::class, 'id', 'account_type');
    }

    public function get_net_salary()
    {
        //allowance
        $allowances      = Allowance::where('employee_id', '=', $this->id)->get();
        $total_allowance = 0;
        foreach ($allowances as $allowance) {
            if ($allowance->type == 'percentage') {
                $employee          = Employee::find($allowance->employee_id);
                $total_allowance  = $allowance->amount * $employee->salary / 100  + $total_allowance;
            } else {
                $total_allowance = $allowance->amount + $total_allowance;
            }
        }

        //commission
        $commissions      = Commission::where('employee_id', '=', $this->id)->get();

        $total_commission = 0;
        foreach ($commissions as $commission) {
            if ($commission->type == 'percentage') {
                $employee          = Employee::find($commission->employee_id);
                $total_commission  = $commission->amount * $employee->salary / 100 + $total_commission;
            } else {
                $total_commission = $commission->amount + $total_commission;
            }
        }

        //Loan
        $loans      = Loan::where('employee_id', '=', $this->id)->get();
        $total_loan = 0;
        foreach ($loans as $loan) {
            if ($loan->type == 'percentage') {
                $employee = Employee::find($loan->employee_id);
                $total_loan  = $loan->amount * $employee->salary / 100   + $total_loan;
            } else {
                $total_loan = $loan->amount + $total_loan;
            }
        }

        //Saturation Deduction
        $saturation_deductions      = SaturationDeduction::where('employee_id', '=', $this->id)->get();
        $total_saturation_deduction = 0;
        foreach ($saturation_deductions as $saturation_deduction) {
            if ($saturation_deduction->type == 'percentage') {
                $employee          = Employee::find($saturation_deduction->employee_id);
                $total_saturation_deduction  = $saturation_deduction->amount * $employee->salary / 100 + $total_saturation_deduction;
            } else {
                $total_saturation_deduction = $saturation_deduction->amount + $total_saturation_deduction;
            }
        }

        //OtherPayment
        $other_payments      = OtherPayment::where('employee_id', '=', $this->id)->get();
        $total_other_payment = 0;
        foreach ($other_payments as $other_payment) {
            if ($other_payment->type == 'percentage') {
                $employee          = Employee::find($other_payment->employee_id);
                $total_other_payment  = $other_payment->amount * $employee->salary / 100  + $total_other_payment;
            } else {
                $total_other_payment = $other_payment->amount + $total_other_payment;
            }
        }

        //Overtime
        $over_times      = Overtime::where('employee_id', '=', $this->id)->get();
        $total_over_time = 0;
        foreach ($over_times as $over_time) {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }

        //CompanyContribution
        $company_contributions = CompanyContribution::where('employee_id', '=', $this->id)->get();
        $total_company_contribution = 0;
        foreach ($company_contributions as $company_contribution) {
            if ($company_contribution->type == 'percentage') {
                $employee          = Employee::find($company_contribution->employee_id);
                $total_company_contribution  = $company_contribution->amount * $employee->salary / 100  + $total_company_contribution;
            } else {
                $total_company_contribution = $company_contribution->amount + $total_company_contribution;
            }
        }

        //Net Salary Calculate
        $advance_salary = $total_allowance + $total_commission + $total_company_contribution - $total_loan - $total_saturation_deduction + $total_other_payment + $total_over_time;
        $employee       = Employee::where('id', '=', $this->id)->first();

        $TotalEarning = $total_allowance + $total_commission + $total_company_contribution + $total_other_payment + $total_over_time + (!empty($employee->salary) ? $employee->salary : 0);
        $taxBrackets = TaxBracket::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        
        $taxRate = 0;
        foreach ($taxBrackets as $bracket) {
            if ($TotalEarning >= $bracket['from'] && $TotalEarning <= $bracket['to'] || $TotalEarning == $bracket['fixed_amount']) {
                $taxRate = $bracket['percentage'];
            }
        }
        $taxAmount = ($TotalEarning * $taxRate) / 100;

        $taxable_earning = $total_allowance + $total_commission + $total_company_contribution + $total_other_payment + $total_over_time + (!empty($employee->salary) ? $employee->salary : 0) - $total_loan - $total_saturation_deduction;

        $net_salary = $taxable_earning - $taxAmount;

        $net_salary     = $net_salary;

        return $net_salary;
    }
    public static function allowance($id)
    {
        //allowance
        $allowances      = Allowance::where('employee_id', '=', $id)->get();
        $total_allowance = 0;
        foreach ($allowances as $allowance) {
            $total_allowance = $allowance->amount + $total_allowance;
        }

        $allowance_json = json_encode($allowances);

        return $allowance_json;
    }

    public static function commission($id)
    {
        //commission
        $commissions      = Commission::where('employee_id', '=', $id)->get();
        $total_commission = 0;

        foreach ($commissions as $commission) {
            $total_commission = $commission->amount + $total_commission;
        }
        $commission_json = json_encode($commissions);

        return $commission_json;
    }

    public static function loan($id)
    {
        //Loan
        $loans      = Loan::where('employee_id', '=', $id)->get();
        $total_loan = 0;
        foreach ($loans as $loan) {
            $total_loan = $loan->amount + $total_loan;
        }
        $loan_json = json_encode($loans);

        return $loan_json;
    }

    public static function saturation_deduction($id)
    {
        //Saturation Deduction
        $saturation_deductions      = SaturationDeduction::where('employee_id', '=', $id)->get();
        $total_saturation_deduction = 0;
        foreach ($saturation_deductions as $saturation_deduction) {
            $total_saturation_deduction = $saturation_deduction->amount + $total_saturation_deduction;
        }
        $saturation_deduction_json = json_encode($saturation_deductions);

        return $saturation_deduction_json;
    }

    public static function other_payment($id)
    {
        //OtherPayment
        $other_payments      = OtherPayment::where('employee_id', '=', $id)->get();
        $total_other_payment = 0;
        foreach ($other_payments as $other_payment) {
            $total_other_payment = $other_payment->amount + $total_other_payment;
        }
        $other_payment_json = json_encode($other_payments);

        return $other_payment_json;
    }

    public static function overtime($id)
    {
        //Overtime
        $over_times      = Overtime::where('employee_id', '=', $id)->get();
        $total_over_time = 0;
        foreach ($over_times as $over_time) {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }
        $over_time_json = json_encode($over_times);

        return $over_time_json;
    }

    public static function companycontribution($id)
    {
        //CompanyContribution
        $company_contributions      = CompanyContribution::where('employee_id', '=', $id)->get();
        $total_company_contribution = 0;
        foreach ($company_contributions as $company_contribution) {
            $total_company_contribution = $company_contribution->amount + $total_company_contribution;
        }

        $company_contribution_json = json_encode($company_contributions);

        return $company_contribution_json;
    }

    public static function TaxBracket($id, $empId, $month)
    {
        //CompanyContribution
        $tax_bracket      = TaxBracket::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        $payslip_data = PaySlip::where('employee_id', $empId)->where('salary_month', $month)->first();

        $totalAllowance = 0;
        $totalCommission = 0;
        $totalotherpayment = 0;
        $ot = 0;
        $totalCompanyContribution = 0;
        $totalloan = 0;
        $totaldeduction = 0;

        if (!empty($payslip_data)) {

            // allowance
            $allowances = json_decode($payslip_data->allowance);
            foreach ($allowances as $allowance) {
                if ($allowance->type == 'percentage') {
                    $empall  = $allowance->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empall = $allowance->amount;
                }
                $totalAllowance += $empall;
            }

            // commission
            $commissions = json_decode($payslip_data->commission);
            foreach ($commissions as $commission) {

                if ($commission->type == 'percentage') {
                    $empcom  = $commission->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empcom = $commission->amount;
                }
                $totalCommission += $empcom;
            }

            // otherpayment
            $otherpayments = json_decode($payslip_data->other_payment);
            foreach ($otherpayments as $otherpayment) {
                if ($otherpayment->type == 'percentage') {
                    $empotherpay  = $otherpayment->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empotherpay = $otherpayment->amount;
                }
                $totalotherpayment += $empotherpay;
            }

            //overtime
            $overtimes = json_decode($payslip_data->overtime);
            foreach ($overtimes as $overtime) {
                $OverTime = $overtime->number_of_days * $overtime->hours * $overtime->rate;
                $ot += $OverTime;
            }

            // companycontribution
            $company_contributions = json_decode($payslip_data->company_contribution);
            foreach ($company_contributions as $company_contribution) {
                if ($company_contribution->type == 'percentage') {
                    $empall  = $company_contribution->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empall = $company_contribution->amount;
                }
                $totalCompanyContribution += $empall;
            }

            // loan
            $loans = json_decode($payslip_data->loan);

            foreach ($loans as $loan) {
                if ($loan->type == 'percentage') {
                    $emploan  = $loan->amount * $payslip_data->basic_salary / 100;
                } else {
                    $emploan = $loan->amount;
                }
                $totalloan += $emploan;
            }

            // saturation_deduction
            $deductions = json_decode($payslip_data->saturation_deduction);
            foreach ($deductions as $deduction) {
                if ($deduction->type == 'percentage') {
                    $empdeduction  = $deduction->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empdeduction = $deduction->amount;
                }
                $totaldeduction += $empdeduction;
            }
        }

        $TotalEarning = $totalAllowance + $totalCommission + $totalCompanyContribution + $totalotherpayment + $ot + (!empty($payslip_data->basic_salary) ? $payslip_data->basic_salary : 0);
        $taxBrackets = TaxBracket::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        $taxRate = 0;
        foreach ($taxBrackets as $bracket) {
            if ($TotalEarning >= $bracket['from'] && $TotalEarning <= $bracket['to'] || $TotalEarning == $bracket['fixed_amount']) {
                $taxRate = $bracket['percentage'];
            }
        }

        $tax_bracket_json = json_encode($taxRate);

        return $tax_bracket_json;
    }

    public static function employeePayslipDetail($employeeId, $month)
    {

        $payslip_data = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->first();
        $totalAllowance = 0;
        $totalCommission = 0;
        $totalotherpayment = 0;
        $ot = 0;
        $totalCompanyContribution = 0;
        $totalloan = 0;
        $totaldeduction = 0;

        if (!empty($payslip_data)) {

            // allowance
            $allowances = json_decode($payslip_data->allowance);
            foreach ($allowances as $allowance) {
                if ($allowance->type == 'percentage') {
                    $empall  = $allowance->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empall = $allowance->amount;
                }
                $totalAllowance += $empall;
            }

            // commission
            $commissions = json_decode($payslip_data->commission);
            foreach ($commissions as $commission) {

                if ($commission->type == 'percentage') {
                    $empcom  = $commission->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empcom = $commission->amount;
                }
                $totalCommission += $empcom;
            }

            // otherpayment
            $otherpayments = json_decode($payslip_data->other_payment);
            foreach ($otherpayments as $otherpayment) {
                if ($otherpayment->type == 'percentage') {
                    $empotherpay  = $otherpayment->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empotherpay = $otherpayment->amount;
                }
                $totalotherpayment += $empotherpay;
            }

            //overtime
            $overtimes = json_decode($payslip_data->overtime);
            foreach ($overtimes as $overtime) {
                $OverTime = $overtime->number_of_days * $overtime->hours * $overtime->rate;
                $ot += $OverTime;
            }

            // companycontribution
            $company_contributions = json_decode($payslip_data->company_contribution);
            foreach ($company_contributions as $company_contribution) {
                if ($company_contribution->type == 'percentage') {
                    $empall  = $company_contribution->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empall = $company_contribution->amount;
                }
                $totalCompanyContribution += $empall;
            }

            // loan
            $loans = json_decode($payslip_data->loan);

            foreach ($loans as $loan) {
                if ($loan->type == 'percentage') {
                    $emploan  = $loan->amount * $payslip_data->basic_salary / 100;
                } else {
                    $emploan = $loan->amount;
                }
                $totalloan += $emploan;
            }

            // saturation_deduction
            $deductions = json_decode($payslip_data->saturation_deduction);
            foreach ($deductions as $deduction) {
                if ($deduction->type == 'percentage') {
                    $empdeduction  = $deduction->amount * $payslip_data->basic_salary / 100;
                } else {
                    $empdeduction = $deduction->amount;
                }
                $totaldeduction += $empdeduction;
            }
        }

        $TotalEarning = $totalAllowance + $totalCommission + $totalCompanyContribution + $totalotherpayment + $ot + (!empty($payslip_data->basic_salary) ? $payslip_data->basic_salary : 0);
        $taxAmount = ($TotalEarning * $payslip_data->tax_bracket) / 100;

        $taxable_earning = $totalAllowance + $totalCommission + $totalCompanyContribution + $totalotherpayment + $ot + (!empty($payslip_data->basic_salary) ? $payslip_data->basic_salary : 0) - $totalloan - $totaldeduction;

        $payslip['payslip']        = $payslip_data;
        $payslip['totalEarning']   = $totalAllowance + $totalCommission + $totalotherpayment + $ot + $totalCompanyContribution + (!empty($payslip_data->basic_salary) ? $payslip_data->basic_salary : 0);
        $payslip['taxable_earning']   = $taxable_earning;
        $payslip['tax_rate']   = $payslip_data->tax_bracket;
        $payslip['tax_amount']   = $taxAmount;
        $payslip['totalDeduction'] = $totalloan + $totaldeduction;

        $payslip['allowance'] = $totalAllowance;
        $payslip['commission'] = $totalCommission;
        $payslip['other_payment'] = $totalotherpayment;
        $payslip['overtime'] = $ot;
        $payslip['company_contribution'] = $totalCompanyContribution;
        $payslip['loan'] = $totalloan;
        $payslip['saturation_deduction'] = $totaldeduction;
        return $payslip;
    }
    public static function countEmployees($id = null)
    {
        if ($id == null) {
            $id = Auth::user()->id;
        }
        return Employee::where('created_by', '=', $id)->count();
    }
    public static function defaultJoiningLetterRegister($user_id)
    {
        foreach ($defaultTemplate as $lang => $content) {
            JoiningLetter::create(
                [
                    'lang' => $lang,
                    'content' => $content,
                    'created_by' => $user_id,

                ]
            );
        }
    }
    public static function defaultdata($company_id = null, $workspace_id = null)
    {
        $company_setting = [
            "employee_prefix" => "#EMP",
            "company_start_time" => "09:00",
            "company_end_time" => "18:00",
            "ip_restrict" => "off",
        ];

        $hr_permission = [
            'hrm manage',
            'hrm dashboard manage',
            'sidebar hrm report manage',
            'document manage',
            'document create',
            'document edit',
            'document delete',
            'attendance manage',
            'attendance create',
            'attendance edit',
            'attendance delete',
            'attendance import',
            'branch manage',
            'branch create',
            'branch edit',
            'branch delete',
            'department manage',
            'department create',
            'department edit',
            'department delete',
            'designation manage',
            'designation create',
            'designation edit',
            'designation delete',
            'employee manage',
            'employee create',
            'employee edit',
            'employee delete',
            'employee show',
            'employee profile manage',
            'employee profile show',
            'employee import',
            'documenttype manage',
            'documenttype create',
            'documenttype edit',
            'documenttype delete',
            'companypolicy manage',
            'companypolicy create',
            'companypolicy edit',
            'companypolicy delete',
            'leave manage',
            'leave create',
            'leave edit',
            'leave delete',
            'leave approver manage',
            'leavetype manage',
            'leavetype create',
            'leavetype edit',
            'leavetype delete',
            'award manage',
            'award create',
            'award edit',
            'award delete',
            'awardtype manage',
            'awardtype create',
            'awardtype edit',
            'awardtype delete',
            'transfer manage',
            'transfer create',
            'transfer edit',
            'transfer delete',
            'resignation manage',
            'resignation create',
            'resignation edit',
            'resignation delete',
            'travel manage',
            'travel create',
            'travel edit',
            'travel delete',
            'promotion manage',
            'promotion create',
            'promotion edit',
            'promotion delete',
            'complaint manage',
            'complaint create',
            'complaint edit',
            'complaint delete',
            'warning manage',
            'warning create',
            'warning edit',
            'warning delete',
            'termination manage',
            'termination create',
            'termination edit',
            'termination delete',
            'termination description',
            'terminationtype manage',
            'terminationtype create',
            'terminationtype edit',
            'terminationtype delete',
            'announcement manage',
            'announcement create',
            'announcement edit',
            'announcement delete',
            'holiday manage',
            'holiday create',
            'holiday edit',
            'holiday delete',
            'holiday import',
            'attendance report manage',
            'leave report manage',
            'payroll report manage',
            'paysliptype manage',
            'paysliptype create',
            'paysliptype edit',
            'paysliptype delete',
            'allowanceoption manage',
            'allowanceoption create',
            'allowanceoption edit',
            'allowanceoption delete',
            'loanoption manage',
            'loanoption create',
            'loanoption edit',
            'loanoption delete',
            'deductionoption manage',
            'deductionoption create',
            'deductionoption edit',
            'deductionoption delete',
            'setsalary manage',
            'setsalary create',
            'setsalary edit',
            'setsalary pay slip manage',
            'setsalary show',
            'allowance manage',
            'allowance create',
            'allowance edit',
            'allowance delete',
            'commission manage',
            'commission create',
            'commission edit',
            'commission delete',
            'loan manage',
            'loan create',
            'loan edit',
            'loan delete',
            'saturation deduction manage',
            'saturation deduction create',
            'saturation deduction edit',
            'saturation deduction delete',
            'other payment manage',
            'other payment create',
            'other payment edit',
            'other payment delete',
            'overtime manage',
            'overtime create',
            'overtime edit',
            'overtime delete',
            'company contribution manage',
            'company contribution create',
            'company contribution edit',
            'company contribution delete',
            'branch name edit',
            'department name edit',
            'designation name edit',
            'event manage',
            'event create',
            'event edit',
            'event delete',
            'sidebar payroll manage',
            'sidebar hr admin manage',
            'letter joining manage',
            'letter certificate manage',
            'letter noc manage',
            'ip restrict manage',
            'ip restrict create',
            'ip restrict edit',
            'ip restrict delete',
            'bulk attendance manage',
            'tax bracket manage',
            'tax bracket create',
            'tax bracket edit',
            'tax bracket delete',
            'tax rebate manage',
            'tax rebate create',
            'tax rebate edit',
            'tax rebate delete',
            'tax threshold manage',
            'tax threshold create',
            'tax threshold edit',
            'tax threshold delete',
            'allowance tax manage',
            'allowance tax create',
            'allowance tax edit',
            'allowance tax delete',
            'user manage',
            'user chat manage',
            'user profile manage',
            'user logs history',
            'workspace manage',
            'roles manage',
        ];

        if ($company_id == Null) {
            $companys = User::where('type', 'company')->get();
            foreach ($companys as $company) {
                $hr_role = Role::where('name', 'hr')->where('created_by', $company->id)->where('guard_name', 'web')->first();
                if (empty($hr_role)) {
                    $hr_role = new Role();
                    $hr_role->name = 'hr';
                    $hr_role->guard_name = 'web';
                    $hr_role->module = 'Hrm';
                    $hr_role->created_by = $company->id;
                    $hr_role->save();

                    foreach ($hr_permission as $permission_v) {
                        $permission = Permission::where('name', $permission_v)->first();
                        if (!empty($permission)) {
                            if (!$hr_role->hasPermission($permission_v)) {
                                $hr_role->givePermission($permission);
                            }
                        }
                    }
                }

                $WorkSpaces = WorkSpace::where('created_by', $company->id)->get();
                foreach ($WorkSpaces as $WorkSpace) {
                    JoiningLetter::defaultJoiningLetter($company->id, $WorkSpace->id);
                    ExperienceCertificate::defaultExpCertificat($company->id, $WorkSpace->id);
                    NOC::defaultNocCertificate($company->id, $WorkSpace->id);

                    // $userContext = new Context(['user_id' => $company->id, 'workspace_id' => !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
                    foreach ($company_setting as $key =>  $p) {
                        // Define the data to be updated or inserted
                        $data = [
                            'key' => $key,
                            'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                            'created_by' => $company->id,
                        ];

                        // Check if the record exists, and update or insert accordingly
                        Setting::updateOrInsert($data, ['value' => $p]);
                    }
                }
            }
        } elseif ($workspace_id == Null) {
            $hr_role = Role::where('name', 'hr')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($hr_role)) {
                $hr_role = new Role();
                $hr_role->name = 'hr';
                $hr_role->guard_name = 'web';
                $hr_role->module = 'Hrm';
                $hr_role->created_by = $company_id;
                $hr_role->save();

                foreach ($hr_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$hr_role->hasPermission($permission_v)) {
                            $hr_role->givePermission($permission);
                        }
                    }
                }
            }

            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by', $company->id)->get();
            foreach ($WorkSpaces as $WorkSpace) {
                JoiningLetter::defaultJoiningLetter($company->id, $WorkSpace->id);
                ExperienceCertificate::defaultExpCertificat($company->id, $WorkSpace->id);
                NOC::defaultNocCertificate($company->id, $WorkSpace->id);
                // $userContext = new Context(['user_id' => $company->id, 'workspace_id' => !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
                foreach ($company_setting as $key =>  $p) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                        'created_by' => $company->id,
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $p]);
                }
            }
        } else {

            $hr_role = Role::where('name', 'hr')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($hr_role)) {
                $hr_role = new Role();
                $hr_role->name = 'hr';
                $hr_role->guard_name = 'web';
                $hr_role->module = 'Hrm';
                $hr_role->created_by = $company_id;
                $hr_role->save();

                foreach ($hr_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$hr_role->hasPermission($permission_v)) {
                            $hr_role->givePermission($permission);
                        }
                    }
                }
            }

            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpace = WorkSpace::where('created_by', $company->id)->where('id', $workspace_id)->first();
            // $userContext = new Context(['user_id' => $company->id, 'workspace_id' => !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
            foreach ($company_setting as $key =>  $p) {
                JoiningLetter::defaultJoiningLetter($company->id, $WorkSpace->id);
                ExperienceCertificate::defaultExpCertificat($company->id, $WorkSpace->id);
                NOC::defaultNocCertificate($company->id, $WorkSpace->id);
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                    'created_by' => $company->id,
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $p]);
            }
        }
    }
    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $staff_permission = [
            'hrm dashboard manage',
            'document manage',
            'attendance manage',
            'employee profile manage',
            'employee profile show',
            'hrm manage',
            'companypolicy manage',
            'leave manage',
            'leave create',
            'leave edit',
            'award manage',
            'transfer manage',
            'resignation manage',
            'travel manage',
            'promotion manage',
            'complaint manage',
            'complaint create',
            'complaint edit',
            'complaint delete',
            'warning manage',
            'termination manage',
            'announcement manage',
            'holiday manage',
            'attendance report manage',
            'leave report manage',
            'setsalary show',
            'setsalary manage',
            'setsalary pay slip manage',
            'allowance manage',
            'commission manage',
            'loan manage',
            'saturation deduction manage',
            'other payment manage',
            'overtime manage',
            'company contribution manage',
            'sidebar hr admin manage',
            'sidebar payroll manage',
            'employee manage',
            'employee show',
        ];

        $hr_permission = [
            'hrm manage',
            'hrm dashboard manage',
            'sidebar hrm report manage',
            'document manage',
            'document create',
            'document edit',
            'document delete',
            'attendance manage',
            'attendance create',
            'attendance edit',
            'attendance delete',
            'attendance import',
            'branch manage',
            'branch create',
            'branch edit',
            'branch delete',
            'department manage',
            'department create',
            'department edit',
            'department delete',
            'designation manage',
            'designation create',
            'designation edit',
            'designation delete',
            'employee manage',
            'employee create',
            'employee edit',
            'employee delete',
            'employee show',
            'employee profile manage',
            'employee profile show',
            'employee import',
            'documenttype manage',
            'documenttype create',
            'documenttype edit',
            'documenttype delete',
            'companypolicy manage',
            'companypolicy create',
            'companypolicy edit',
            'companypolicy delete',
            'leave manage',
            'leave create',
            'leave edit',
            'leave delete',
            'leave approver manage',
            'leavetype manage',
            'leavetype create',
            'leavetype edit',
            'leavetype delete',
            'award manage',
            'award create',
            'award edit',
            'award delete',
            'awardtype manage',
            'awardtype create',
            'awardtype edit',
            'awardtype delete',
            'transfer manage',
            'transfer create',
            'transfer edit',
            'transfer delete',
            'resignation manage',
            'resignation create',
            'resignation edit',
            'resignation delete',
            'travel manage',
            'travel create',
            'travel edit',
            'travel delete',
            'promotion manage',
            'promotion create',
            'promotion edit',
            'promotion delete',
            'complaint manage',
            'complaint create',
            'complaint edit',
            'complaint delete',
            'warning manage',
            'warning create',
            'warning edit',
            'warning delete',
            'termination manage',
            'termination create',
            'termination edit',
            'termination delete',
            'termination description',
            'terminationtype manage',
            'terminationtype create',
            'terminationtype edit',
            'terminationtype delete',
            'announcement manage',
            'announcement create',
            'announcement edit',
            'announcement delete',
            'holiday manage',
            'holiday create',
            'holiday edit',
            'holiday delete',
            'holiday import',
            'attendance report manage',
            'leave report manage',
            'payroll report manage',
            'paysliptype manage',
            'paysliptype create',
            'paysliptype edit',
            'paysliptype delete',
            'allowanceoption manage',
            'allowanceoption create',
            'allowanceoption edit',
            'allowanceoption delete',
            'loanoption manage',
            'loanoption create',
            'loanoption edit',
            'loanoption delete',
            'deductionoption manage',
            'deductionoption create',
            'deductionoption edit',
            'deductionoption delete',
            'setsalary manage',
            'setsalary create',
            'setsalary edit',
            'setsalary pay slip manage',
            'setsalary show',
            'allowance manage',
            'allowance create',
            'allowance edit',
            'allowance delete',
            'commission manage',
            'commission create',
            'commission edit',
            'commission delete',
            'loan manage',
            'loan create',
            'loan edit',
            'loan delete',
            'saturation deduction manage',
            'saturation deduction create',
            'saturation deduction edit',
            'saturation deduction delete',
            'other payment manage',
            'other payment create',
            'other payment edit',
            'other payment delete',
            'overtime manage',
            'overtime create',
            'overtime edit',
            'overtime delete',
            'company contribution manage',
            'company contribution create',
            'company contribution edit',
            'company contribution delete',
            'branch name edit',
            'department name edit',
            'designation name edit',
            'event manage',
            'event create',
            'event edit',
            'event delete',
            'sidebar payroll manage',
            'sidebar hr admin manage',
            'letter joining manage',
            'letter certificate manage',
            'letter noc manage',
            'ip restrict manage',
            'ip restrict create',
            'ip restrict edit',
            'ip restrict delete',
            'bulk attendance manage',
            'tax bracket manage',
            'tax bracket create',
            'tax bracket edit',
            'tax bracket delete',
            'tax rebate manage',
            'tax rebate create',
            'tax rebate edit',
            'tax rebate delete',
            'tax threshold manage',
            'tax threshold create',
            'tax threshold edit',
            'tax threshold delete',
            'allowance tax manage',
            'allowance tax create',
            'allowance tax edit',
            'allowance tax delete',
            'user manage',
            'user chat manage',
            'user profile manage',
            'user logs history',
            'workspace manage',
            'roles manage',

        ];

        if ($role_id == Null) {

            // staff
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$role->hasPermission($permission_v)) {
                            $role->givePermission($permission);
                        }
                    }
                }
            }

            // hr
            $roles_v = Role::where('name', 'hr')->get();

            foreach ($roles_v as $role) {
                foreach ($hr_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$role->hasPermission($permission_v)) {
                            $role->givePermission($permission);
                        }
                    }
                }
            }
        } else {
            if ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }

            if ($rolename == 'hr') {
                $roles_v = Role::where('name', 'hr')->where('id', $role_id)->first();
                foreach ($hr_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }
        }
    }
    public static function PayrollCalculation($EmpID = null, $months = null, $type = null)
    {
        if (!empty($EmpID) && !empty($type) && count($months) > 0) {
            $data = [];
            foreach ($months as $key => $month) {
                $payslip_data = Employee::employeePayslipDetail($EmpID, $month);
                $data[] = $payslip_data[$type];
            }
            $data[] = array_sum($data);
            return $data;
        } else {
            return [];
        }
    }
}
