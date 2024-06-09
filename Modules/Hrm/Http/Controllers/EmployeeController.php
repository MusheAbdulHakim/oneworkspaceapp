<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\DocumentType;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\EmployeeDocument;
use Modules\Hrm\Entities\ExperienceCertificate;
use Modules\Hrm\Entities\JoiningLetter;
use Modules\Hrm\Entities\NOC;
use Modules\Hrm\Entities\PaySlip;
use Modules\Hrm\Entities\Termination;
use Modules\Hrm\Events\CreateEmployee;
use Modules\Hrm\Events\DestroyEmployee;
use Modules\Hrm\Events\UpdateEmployee;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->where('users.id', Auth::user()->id)
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id', 'branches.name as branches_name', 'departments.name as departments_name', 'designations.name as designations_name')
                ->get();
            return view('hrm::employee.index', compact('employees'));
        } elseif (Auth::user()->isAbleTo('employee manage')) {
            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->where('users.created_by', creatorId())->emp()
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id', 'branches.name as branches_name', 'departments.name as departments_name', 'designations.name as designations_name')
                ->get();

            return view('hrm::employee.index', compact('employees'));
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
        if (Auth::user()->isAbleTo('employee create')) {
            $role             = Role::where('created_by', creatorId())->whereNotIn('name', Auth::user()->not_emp_type)->get()->pluck('name', 'id');
            $documents        = DocumentType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $branches         = Branch::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments      = Department::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations     = Designation::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $employees        = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            $employeesId      = Employee::employeeIdFormat($this->employeeNumber());
            $location_type    = Employee::$location_type;
            if (module_is_active('CustomField')) {
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'hrm')->where('sub_module', 'Employee')->get();
            } else {
                $customFields = null;
            }
            return view('hrm::employee.create', compact('employees', 'employeesId', 'departments', 'designations', 'documents', 'branches', 'role', 'customFields', 'location_type'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $canUse =  PlanCheck('User', Auth::user()->id);
        if ($canUse == false) {
            return redirect()->back()->with('error', 'You have maxed out the total number of Employee allowed on your current plan');
        }
        $roles            = Role::where('created_by', creatorId())->where('id', $request->role)->first();
        if (Auth::user()->isAbleTo('employee create')) {

            $rules = [
                'dob' => 'before:' . date('Y-m-d'),
                'gender' => 'required',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'address' => 'required',
                'branch_id' => 'required',
                'department_id' => 'required',
                'designation_id' => 'required'
            ];
            if (module_is_active('BiometricAttendance')) {
                $rules['biometric_emp_id'] = [
                    'required',
                    Rule::unique('employees')->where(function ($query) {
                        return $query->where('created_by', creatorId())->where('workspace', getActiveWorkSpace());
                    })
                ];
            }

            $validator = \Validator::make(
                $request->all(),
                $rules
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }
            if (isset($request->user_id)) {
                $user = User::where('id', $request->user_id)->first();
            } else {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'workspace_id' => getActiveWorkSpace(),
                        'active_workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ]
                );
                $user->save();

                $user->addRole($roles);
            }
            if (empty($user)) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if ($user->name != $request->name) {
                $user->name = $request->name;
                $user->save();
            }
            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            if (isset($request->payment_requires_work_advice)) {
                $payment_requires_work_advice = $request->payment_requires_work_advice;
            } else {
                $payment_requires_work_advice = 'off';
            }

            $employee = Employee::create(
                [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'dob' => $request['dob'],
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'email' => $user->email,
                    'passport_country' => $request['passport_country'],
                    'passport' => $request['passport'],
                    'location_type' => $request['location_type'],
                    'country' => $request['country'],
                    'state' => $request['state'],
                    'city' => $request['city'],
                    'zipcode' => $request['zipcode'],
                    'address' => $request['address'],
                    'employee_id' => $this->employeeNumber(),
                    'branch_id' => $request['branch_id'],
                    'department_id' => $request['department_id'],
                    'designation_id' => $request['designation_id'],
                    'company_doj' => $request['company_doj'],
                    'documents' => $document_implode,
                    'account_holder_name' => $request['account_holder_name'],
                    'account_number' => $request['account_number'],
                    'bank_name' => $request['bank_name'],
                    'bank_identifier_code' => $request['bank_identifier_code'],
                    'branch_location' => $request['branch_location'],
                    'tax_payer_id' => $request['tax_payer_id'],
                    'hours_per_day' => $request['hours_per_day'],
                    'annual_salary' => $request['annual_salary'],
                    'days_per_week' => $request['days_per_week'],
                    'fixed_salary' => $request['fixed_salary'],
                    'hours_per_month' => $request['hours_per_month'],
                    'rate_per_day' => $request['rate_per_day'],
                    'days_per_month' => $request['days_per_month'],
                    'rate_per_hour' => $request['rate_per_hour'],
                    'payment_requires_work_advice' => $payment_requires_work_advice,
                    'workspace' => $user->workspace_id,
                    'created_by' => $user->created_by,
                ]
            );

            if ($request->hasFile('document')) {
                foreach ($request->document as $key => $document) {

                    $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    $uplaod = multi_upload_file($document, 'document', $fileNameToStore, 'emp_document');
                    if ($uplaod['flag'] == 1) {
                        $url = $uplaod['url'];
                    } else {
                        return redirect()->back()->with('error', $uplaod['msg']);
                    }
                    $employee_document = EmployeeDocument::create(
                        [
                            'employee_id' => $employee['employee_id'],
                            'document_id' => $key,
                            'document_value' => !empty($url) ? $url : '',
                            'workspace' => $user->workspace_id,
                            'created_by' => creatorId(),
                        ]
                    );
                    $employee_document->save();
                }
            }
            if (module_is_active('CustomField')) {
                \Modules\CustomField\Entities\CustomField::saveData($employee, $request->customField);
            }

            event(new CreateEmployee($request, $employee));

            return redirect()->route('employee.index')->with('success', __('Employee successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('employee show')) {
            try {
                $empId        = Crypt::decrypt($id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Employee Not Found.'));
            }
            $company_settings = getCompanyAllSetting();
            $employee     = Employee::where('user_id', $empId)->where('workspace', getActiveWorkSpace())->first();
            if (!empty($employee)) {
                $documents    = DocumentType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
                $payslips    = PaySlip::where('employee_id', $employee->id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->latest()->get();
                $user         = User::where('id', $empId)->where('workspace_id', getActiveWorkSpace())->first();
                $employeesId  = Employee::employeeIdFormat($employee->employee_id);
                $location_type    = Employee::$location_type;
                if (module_is_active('CustomField')) {
                    $employee->customField = \Modules\CustomField\Entities\CustomField::getData($employee, 'hrm', 'Employee');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'hrm')->where('sub_module', 'Employee')->get();
                } else {
                    $customFields = null;
                }
                return view('hrm::employee.show', compact('employee', 'user', 'employeesId', 'documents', 'customFields', 'location_type', 'payslips', 'company_settings'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Employee Not Found.'));
        }

        if (Auth::user()->isAbleTo('employee edit')) {
            $document_types = DocumentType::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $employee     = Employee::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();
            $user         = User::where('id', $id)->where('workspace_id', getActiveWorkSpace())->first();
            $location_type    = Employee::$location_type;
            if (!empty($employee)) {
                if (module_is_active('CustomField')) {
                    $employee->customField = \Modules\CustomField\Entities\CustomField::getData($employee, 'hrm', 'Employee');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'hrm')->where('sub_module', 'Employee')->get();
                } else {
                    $customFields = null;
                }
                $employeesId  = Employee::employeeIdFormat($employee->employee_id);
            } else {
                if (module_is_active('CustomField')) {
                    $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'hrm')->where('sub_module', 'Employee')->get();
                } else {
                    $customFields = null;
                }
                $employeesId  = Employee::employeeIdFormat($this->employeeNumber());
            }

            return view('hrm::employee.edit', compact('employee', 'user', 'employeesId', 'branches', 'departments', 'designations', 'document_types', 'customFields', 'location_type'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('employee edit')) {

            $rules = [
                'dob' => 'required',
                'gender' => 'required',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'address' => 'required',
            ];

            $employee = Employee::findOrFail($id);
            if (module_is_active('BiometricAttendance')) {
                if ($request->has('biometric_emp_id') && $employee->biometric_emp_id != $request->biometric_emp_id) {
                    $rules['biometric_emp_id'] = [
                        'required',
                        Rule::unique('employees')->where(function ($query) {
                            return $query->where('created_by', creatorId())->where('workspace', getActiveWorkSpace());
                        })
                    ];
                }
            }

            $validator = \Validator::make(
                $request->all(),
                $rules
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $user = User::where('id', $request->user_id)->first();
            if (empty($user)) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if ($user->name != $request->name) {
                $user->name = $request->name;
                $user->save();
            }
            $employee = Employee::findOrFail($id);
            if ($request->document) {
                foreach ($request->document as $key => $document) {
                    if (!empty($document)) {
                        $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                        $uplaod = multi_upload_file($document, 'document', $fileNameToStore, 'emp_document');
                        if ($uplaod['flag'] == 1) {
                            $url = $uplaod['url'];
                        } else {
                            return redirect()->back()->with('error', $uplaod['msg']);
                        }


                        $employee_document = EmployeeDocument::where('employee_id', $employee->employee_id)->where('document_id', $key)->first();

                        if (!empty($employee_document)) {
                            if (!empty($employee_document->document_value)) {
                                delete_file($employee_document->document_value);
                            }
                            $employee_document->document_value = $url;
                            $employee_document->save();
                        } else {
                            $employee_document                 = new EmployeeDocument();
                            $employee_document->employee_id    = $employee->employee_id;
                            $employee_document->document_id    = $key;
                            $employee_document->document_value = $url;
                            $employee_document->save();
                        }
                    }
                }
            }
            $input    = $request->all();
            $employee->fill($input)->save();
            if (module_is_active('CustomField')) {
                \Modules\CustomField\Entities\CustomField::saveData($employee, $request->customField);
            }

            event(new UpdateEmployee($request, $employee));

            return redirect()->route('employee.index')->with('success', 'Employee successfully updated.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('employee delete')) {
            $employee     = Employee::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();
            if (!empty($employee)) {

                $emp_documents = EmployeeDocument::where('employee_id', $employee->employee_id)->get();
                $pay_slips = PaySlip::where('employee_id', $employee->id)->get();
                foreach ($emp_documents as $emp_document) {
                    if (!empty($emp_document->document_value)) {
                        delete_file($emp_document->document_value);
                    }
                    $emp_document->delete();
                }
                foreach ($pay_slips as $pay_slip) {
                    if (!empty($pay_slip)) {
                        $pay_slip->delete();
                    }
                }
                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'Hrm')->where('sub_module', 'Employee')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $employee->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }
                event(new DestroyEmployee($employee));
                $employee->delete();
            } else {
                return redirect()->back()->with('error', __('employee already delete.'));
            }

            return redirect()->back()->with('success', 'Employee successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function employeeNumber()
    {
        $latest = Employee::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->employee_id + 1;
    }

    public function getdepartment(Request $request)
    {
        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('branch_id', $request->branch_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($departments);
    }

    public function getdDesignation(Request $request)
    {
        if ($request->department_id == 0) {
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        } else {
            $designations = Designation::where('department_id', $request->department_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($designations);
    }

    public function grid()
    {
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->where('users.id', Auth::user()->id)
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id', 'branches.name as branches_name', 'departments.name as departments_name', 'designations.name as designations_name')
                ->get();
            return view('hrm::employee.grid', compact('employees'));
        } elseif (Auth::user()->isAbleTo('employee manage')) {
            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->where('users.created_by', creatorId())->emp()
                ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id', 'branches.name as branches_name', 'departments.name as departments_name', 'designations.name as designations_name')
                ->get();
            return view('hrm::employee.grid', compact('employees'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function joiningletterPdf($id)
    {

        $currantLang = getActiveLanguage();
        $joiningletter = JoiningLetter::where('lang', $currantLang)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $date = date('Y-m-d');
        $employees = Employee::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $company_settings = getCompanyAllSetting();
        $secs = strtotime(!empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00') - strtotime("00:00");
        $result = date("H:i", strtotime(!empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00') - $secs);
        $obj = [
            'date' =>  company_date_formate($date),
            'app_name' => env('APP_NAME'),
            'employee_name' => $employees->name,
            'address' => !empty($employees->address) ? $employees->address : '',
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'start_date' => company_date_formate(!empty($employees->company_doj) ? $employees->company_doj : ''),
            'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
            'start_time' => !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00',
            'end_time' => !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00',
            'total_hours' => $result,
        ];

        $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
        return view('hrm::employee.template.joiningletterpdf', compact('joiningletter', 'employees'));
    }

    public function joiningletterDoc($id)
    {
        $currantLang = getActiveLanguage();
        $joiningletter = JoiningLetter::where('lang', $currantLang)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $company_settings = getCompanyAllSetting();
        $date = date('Y-m-d');
        $employees = Employee::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $secs = strtotime(!empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00') - strtotime("00:00");
        $result = date("H:i", strtotime(!empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00') - $secs);

        $obj = [
            'date' =>  company_date_formate($date),
            'app_name' => env('APP_NAME'),
            'employee_name' => $employees->name,
            'address' => !empty($employees->address) ? $employees->address : '',
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
            'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
            'start_time' => !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00',
            'end_time' => !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00',
            'total_hours' => $result,

        ];
        $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
        return view('hrm::employee.template.joiningletterdocx', compact('joiningletter', 'employees'));
    }

    public function ExpCertificatePdf($id)
    {
        $currantLang = getActiveLanguage();
        if (!isset($currantLang)) {
            $currantLang = 'en';
        }
        $termination = Termination::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $experience_certificate = ExperienceCertificate::where('lang', $currantLang)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $company_settings = getCompanyAllSetting();
        $date = date('Y-m-d');
        $employees = Employee::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $secs = strtotime(!empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00') - strtotime("00:00");
        $result = date("H:i", strtotime(!empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00') - $secs);
        $date1 = date_create($employees->company_doj);
        $date2 = date_create($employees->termination_date);
        $diff  = date_diff($date1, $date2);
        $duration = $diff->format("%a days");

        if (!empty($termination->termination_date)) {

            $obj = [
                'date' =>  company_date_formate($date),
                'app_name' => env('APP_NAME'),
                'employee_name' => $employees->name,
                'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                'duration' => $duration,
                'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',

            ];
        } else {
            return redirect()->back()->with('error', __('Termination date is required.'));
        }


        $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
        return view('hrm::employee.template.ExpCertificatepdf', compact('experience_certificate', 'employees'));
    }

    public function ExpCertificateDoc($id)
    {
        $currantLang = getActiveLanguage();
        if (!isset($currantLang)) {
            $currantLang = 'en';
        }
        $termination = Termination::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $experience_certificate = ExperienceCertificate::where('lang', $currantLang)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $date = date('Y-m-d');
        $employees = Employee::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $company_settings = getCompanyAllSetting();
        $secs = strtotime(!empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00') - strtotime("00:00");
        $result = date("H:i", strtotime(!empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00') - $secs);
        $date1 = date_create($employees->company_doj);
        $date2 = date_create($employees->termination_date);
        $diff  = date_diff($date1, $date2);
        $duration = $diff->format("%a days");
        if (!empty($termination->termination_date)) {
            $obj = [
                'date' =>  company_date_formate($date),
                'app_name' => env('APP_NAME'),
                'employee_name' => $employees->name,
                'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                'duration' => $duration,
                'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',

            ];
        } else {
            return redirect()->back()->with('error', __('Termination date is required.'));
        }

        $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
        return view('hrm::employee.template.ExpCertificatedocx', compact('experience_certificate', 'employees'));
    }

    public function NocPdf($id)
    {
        $users = \Auth::user();

        $currantLang = getActiveLanguage();
        $noc_certificate = NOC::where('lang', $currantLang)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $date = date('Y-m-d');
        $employees = Employee::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $company_settings = getCompanyAllSetting();
        $secs = strtotime(!empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00') - strtotime("00:00");
        $result = date("H:i", strtotime(!empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00') - $secs);
        $obj = [
            'date' => company_date_formate($date),
            'employee_name' => $employees->name,
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'app_name' => env('APP_NAME'),
        ];
        $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
        return view('hrm::employee.template.Nocpdf', compact('noc_certificate', 'employees'));
    }

    public function NocDoc($id)
    {
        $users = \Auth::user();

        $currantLang = getActiveLanguage();
        $noc_certificate = NOC::where('lang', $currantLang)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $date = date('Y-m-d');
        $employees = Employee::where('employee_id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $company_settings = getCompanyAllSetting();
        $secs = strtotime(!empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00') - strtotime("00:00");
        $result = date("H:i", strtotime(!empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00') - $secs);
        $obj = [
            'date' =>  company_date_formate($date),
            'employee_name' => $employees->name,
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'app_name' => env('APP_NAME'),
        ];
        $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
        return view('hrm::employee.template.Nocdocx', compact('noc_certificate', 'employees'));
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('employee import')) {
            return view('hrm::employee.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('employee import')) {
            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);
                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                    <option value="name">Name</option>
                                    <option value="dob">DOB</option>
                                    <option value="gender">Gender</option>
                                    <option value="phone">Phone</option>
                                    <option value="address">Address</option>
                                    <option value="email">Email</option>
                                    <option value="password">Password</option>
                                    <option value="company_doj">Company Doj</option>
                                    <option value="account_holder_name">Account Holder Name</option>
                                    <option value="account_number">Account Number</option>
                                    <option value="bank_name">Bank Name</option>
                                    <option value="bank_identifier_code">Bank Identifier Code</option>
                                    <option value="tax_payer_id">Tax Payer Id</option>
                                    </select>
                                </th>
                                ';
                    }
                    $html .= '
                                <th>
                                        <select name="set_column_data branch_name" class="form-control set_column_data branch-name" data-column_number="' . $count . '">
                                            <option value="branch">Branch</option>
                                        </select>
                                </th>
                                ';
                    $html .= '
                                <th>
                                        <select name="set_column_data department_name" class="form-control set_column_data department-name" data-column_number="' . $count . '">
                                            <option value="department">Department</option>
                                        </select>
                                </th>
                                ';
                    $html .= '
                                <th>
                                        <select name="set_column_data designation_name" class="form-control set_column_data designation-name" data-column_number="' . $count . '">
                                            <option value="designation">Designation</option>
                                        </select>
                                </th>
                                ';
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }
                        $html .= '<td>
                                    <select name="branch_name" class="form-control branch-name-value" id="branch_name" required>;';
                        $branchs = Branch::where('created_by', \Auth::user()->id)->pluck('name', 'id');
                        foreach ($branchs as $key => $branch) {
                            $html .= ' <option value="' . $key . '">' . $branch . '</option>';
                        }
                        $html .= '  </select>
                                </td>';

                        $html .= '<td>
                                    <select name="department_name" class="form-control department-name-value" id="department_name" required>;';
                        $departments = Department::where('created_by', \Auth::user()->id)->pluck('name', 'id');
                        foreach ($departments as $key => $department) {
                            $html .= ' <option value="' . $key . '">' . $department . '</option>';
                        }
                        $html .= '  </select>
                                </td>';

                        $html .= '<td>
                                    <select name="designation_name" class="form-control designation-name-value" id="designation_name" required>;';
                        $designations = Designation::where('created_by', \Auth::user()->id)->pluck('name', 'id');
                        foreach ($designations as $key => $designation) {
                            $html .= ' <option value="' . $key . '">' . $designation . '</option>';
                        }
                        $html .= '  </select>
                                </td>';

                        $html .= '</tr>';

                        $temp_data[] = $row;
                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );
            echo json_encode($output);
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportModal()
    {
        if (Auth::user()->isAbleTo('employee import')) {
            return view('hrm::employee.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function employeeImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('employee import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = \Auth::user();

            $roles = Role::where('created_by', creatorId())->where('name', 'staff')->first();
            foreach ($file_data as $key => $row) {
                $employees = Employee::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->Where('email', 'like', $row[$request->email])->get();
                $branch = Branch::find($request->branch[$key]);
                $department = Department::find($request->department[$key]);
                $designation = Designation::find($request->designation[$key]);

                if ($employees->isEmpty()) {

                    try {
                        $user = User::create(
                            [
                                'name' => $row[$request->name],
                                'email' => $row[$request->email],
                                'password' => Hash::make($row[$request->password]),
                                'email_verified_at' => date('Y-m-d h:i:s'),
                                'type' => !empty($roles->name) ? $roles->name : 'staff',
                                'lang' => 'en',
                                'workspace_id' => getActiveWorkSpace(),
                                'active_workspace' => getActiveWorkSpace(),
                                'created_by' => creatorId(),
                            ]
                        );
                        $user->addRole($roles);
                        Employee::create([
                            'name' => $row[$request->name],
                            'user_id' => $user->id,
                            'dob' => $row[$request->dob],
                            'gender' => $row[$request->gender],
                            'phone' => $row[$request->phone],
                            'address' => $row[$request->address],
                            'email' => $row[$request->email],
                            'password' => Hash::make($row[$request->password]),
                            'employee_id' => $this->employeeNumber(),
                            'company_doj' => $row[$request->company_doj],
                            'account_holder_name' => $row[$request->account_holder_name],
                            'account_number' => $row[$request->account_number],
                            'bank_name' => $row[$request->bank_name],
                            'bank_identifier_code' => $row[$request->bank_identifier_code],
                            'tax_payer_id' => $row[$request->tax_payer_id],
                            'branch_id' => !empty($branch) ? $branch->id : 0,
                            'department_id' => !empty($department) ? $department->id : 0,
                            'designation_id' => !empty($designation) ? $designation->id : 0,
                            'created_by' => creatorId(),
                            'workspace' => getActiveWorkSpace(),
                        ]);
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->dob] . '</td>';
                        $html .= '<td>' . $row[$request->gender] . '</td>';
                        $html .= '<td>' . $row[$request->phone] . '</td>';
                        $html .= '<td>' . $row[$request->address] . '</td>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->password] . '</td>';
                        $html .= '<td>' . $row[$request->company_doj] . '</td>';
                        $html .= '<td>' . $row[$request->account_holder_name] . '</td>';
                        $html .= '<td>' . $row[$request->account_number] . '</td>';
                        $html .= '<td>' . $row[$request->bank_name] . '</td>';
                        $html .= '<td>' . $row[$request->bank_identifier_code] . '</td>';
                        $html .= '<td>' . $row[$request->account_holder_name] . '</td>';
                        $html .= '<td>' . $row[$branch->id] . '</td>';
                        $html .= '<td>' . $row[$department->id] . '</td>';
                        $html .= '<td>' . $row[$designation->id] . '</td>';

                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->name] . '</td>';
                    $html .= '<td>' . $row[$request->dob] . '</td>';
                    $html .= '<td>' . $row[$request->gender] . '</td>';
                    $html .= '<td>' . $row[$request->phone] . '</td>';
                    $html .= '<td>' . $row[$request->address] . '</td>';
                    $html .= '<td>' . $row[$request->email] . '</td>';
                    $html .= '<td>' . $row[$request->password] . '</td>';
                    $html .= '<td>' . $row[$request->company_doj] . '</td>';
                    $html .= '<td>' . $row[$request->account_holder_name] . '</td>';
                    $html .= '<td>' . $row[$request->account_number] . '</td>';
                    $html .= '<td>' . $row[$request->bank_name] . '</td>';
                    $html .= '<td>' . $row[$request->bank_identifier_code] . '</td>';
                    $html .= '<td>' . $row[$request->account_holder_name] . '</td>';
                    $html .= '<td>' . $row[$branch->id] . '</td>';
                    $html .= '<td>' . $row[$department->id] . '</td>';
                    $html .= '<td>' . $row[$designation->id] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1) {

                return response()->json([
                    'html' => true,
                    'response' => $html,
                ]);
            } else {
                return response()->json([
                    'html' => false,
                    'response' => 'Data Imported Successfully',
                ]);
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
