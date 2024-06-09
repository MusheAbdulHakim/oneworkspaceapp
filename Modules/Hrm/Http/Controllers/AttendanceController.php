<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Attendance;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\IpRestrict;
use Modules\Hrm\Events\CreateMarkAttendance;
use Modules\Hrm\Events\DestroyMarkAttendance;
use Modules\Hrm\Events\UpdateBulkAttendance;
use Modules\Hrm\Events\UpdateMarkAttendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('attendance manage')) {
            $currentWorkspace = getActiveWorkSpace();
            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $branch->prepend('All', '');

            $department = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $department->prepend('All', '');

            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $attendances = Attendance::where('employee_id', Auth::user()->id)->where('workspace', getActiveWorkSpace());
                if ($request->type == 'monthly' && !empty($request->month)) {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $start_date = date($year . '-' . $month . '-01');
                    $end_date   = date($year . '-' . $month . '-t');

                    $attendances->whereBetween(
                        'date',
                        [
                            $start_date,
                            $end_date,
                        ]
                    );
                } elseif ($request->type == 'daily' && !empty($request->date)) {
                    $attendances->where('date', $request->date);
                } else {
                    $month      = date('m');
                    $year       = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                    $end_date   = date($year . '-' . $month . '-t');

                    $attendances->whereBetween(
                        'date',
                        [
                            $start_date,
                            $end_date,
                        ]
                    );
                }
                $attendances = $attendances->get();
            } else {
                $employee = User::where('workspace_id', getActiveWorkSpace())
                    ->leftjoin('employees', 'users.id', '=', 'employees.user_id')
                    ->where('users.created_by', creatorId())->emp()
                    ->select('users.id');
                if (!empty($request->branch)) {
                    $employee->where('branch_id', $request->branch);
                }

                if (!empty($request->department)) {
                    $employee->where('department_id', $request->department);
                }
                $employee = $employee->get()->pluck('id');

                $attendances = Attendance::whereIn('employee_id', $employee)->where('workspace', getActiveWorkSpace())->with('employees');

                if ($request->type == 'monthly' && !empty($request->month)) {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-' . $month . '-' . $year));

                    $attendances->whereBetween(
                        'date',
                        [
                            $start_date,
                            $end_date,
                        ]
                    );
                } elseif ($request->type == 'daily' && !empty($request->date)) {
                    $attendances->where('date', $request->date);
                } else {

                    $month      = date('m');
                    $year       = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                    $end_date   = date($year . '-' . $month . '-t');

                    $attendances->whereBetween(
                        'date',
                        [
                            $start_date,
                            $end_date,
                        ]
                    );
                }

                $attendances = $attendances->get();
            }

            return view('hrm::attendance.index', compact('attendances', 'branch', 'department'));
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
        if (Auth::user()->isAbleTo('attendance create')) {
            $currentWorkspace = getActiveWorkSpace();
            $employees = User::where('workspace_id', $currentWorkspace)->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');
            $employees->prepend('Select Employee', '');
            return view('hrm::attendance.create', compact('employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('attendance create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'date' => 'required',
                    'clock_in' => 'required',
                    'clock_out' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $company_settings = getCompanyAllSetting();
            $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
            $endTime  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';

            $attendance = Attendance::where('employee_id', '=', $request->employee_id)->where('workspace', getActiveWorkSpace())->where('date', '=', $request->date)->where('clock_out', '=', '00:00:00')->get()->toArray();
            if ($attendance) {
                return redirect()->route('attendanceemployee.index')->with('error', __('Employee Attendance Already Created.'));
            } else {
                $date = date("Y-m-d");

                $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);
                if ($totalLateSeconds < 0) {
                    $late = '0:00:00';
                } else {
                    $hours = floor($totalLateSeconds / 3600);
                    $mins  = floor($totalLateSeconds / 60 % 60);
                    $secs  = floor($totalLateSeconds % 60);
                    $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                }

                //early Leaving
                $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                if ($totalEarlyLeavingSeconds < 0) {
                    $earlyLeaving = '0:00:00';
                } else {
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                }

                if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                } else {
                    $overtime = '00:00:00';
                }
                $employeeAttendance                = new Attendance();
                $employeeAttendance->employee_id   = $request->employee_id;
                $employeeAttendance->date          = $request->date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $request->clock_in . ':00';
                $employeeAttendance->clock_out     = $request->clock_out . ':00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = $earlyLeaving;
                $employeeAttendance->overtime      = $overtime;
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->workspace     = getActiveWorkSpace();
                $employeeAttendance->created_by    = creatorId();
                $employeeAttendance->save();

                event(new CreateMarkAttendance($request, $employeeAttendance));

                return redirect()->route('attendance.index')->with('success', __('Employee attendance successfully created.'));
            }
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
        if (Auth::user()->isAbleTo('attendance edit')) {
            $currentWorkspace = getActiveWorkSpace();
            $attendance = Attendance::where('id', $id)->first();
            $employees = User::where('workspace_id', $currentWorkspace)->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');
            return view('hrm::attendance.edit', compact('attendance', 'employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
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
        if (!empty($request->employee_id)) {
            $employeeId = $request->employee_id;
        } else {
            $employeeId      = Auth::user()->id;
        }
        $todayAttendance = Attendance::where('employee_id', '=', $employeeId)->where('workspace', getActiveWorkSpace())->where('date', '=', date('Y-m-d'))->first();
        $company_settings = getCompanyAllSetting();
        $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
        $endTime  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {

            if (!empty($company_settings['defult_timezone'])) {
                date_default_timezone_set($company_settings['defult_timezone']);
            }
            $date = date("Y-m-d");
            $time = date("H:i");
            //early Leaving
            $totalEarlyLeavingSeconds = strtotime($date . $endTime) - time();
            if ($totalEarlyLeavingSeconds < 0) {
                $earlyLeaving = '0:00:00';
            } else {
                $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                $secs                     = floor($totalEarlyLeavingSeconds % 60);
                $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            }
            if (time() > strtotime($date . $endTime)) {
                //Overtime
                $totalOvertimeSeconds = time() - strtotime($date . $endTime);
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            } else {
                $overtime = '00:00:00';
            }

            $attendance                = Attendance::find($id);
            $attendance->clock_out     = $time;
            $attendance->early_leaving = $earlyLeaving;
            $attendance->overtime      = $overtime;
            $attendance->save();

            event(new UpdateMarkAttendance($request, $attendance));

            return redirect()->back()->with('success', __('Employee Successfully Clock Out.'));
        } else {
            $date = date("Y-m-d");
            //late
            $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);
            if ($totalLateSeconds < 0) {
                $late = '0:00:00';
            } else {
                $hours = floor($totalLateSeconds / 3600);
                $mins  = floor($totalLateSeconds / 60 % 60);
                $secs  = floor($totalLateSeconds % 60);
                $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            }

            //early Leaving
            $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
            if ($totalEarlyLeavingSeconds < 0) {
                $earlyLeaving = '0:00:00';
            } else {
                $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                $secs                     = floor($totalEarlyLeavingSeconds % 60);
                $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            }


            if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                //Overtime
                $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            } else {
                $overtime = '00:00:00';
            }

            $attendance                = Attendance::find($id);
            $attendance->employee_id   = $request->employee_id;
            $attendance->date          = $request->date;
            $attendance->clock_in      = $request->clock_in;
            $attendance->clock_out     = $request->clock_out;
            $attendance->late          = $late;
            $attendance->early_leaving = $earlyLeaving;
            $attendance->overtime      = $overtime;
            $attendance->total_rest    = '00:00:00';

            $attendance->save();

            event(new UpdateMarkAttendance($request, $attendance));

            return redirect()->route('attendance.index')->with('success', __('Employee attendance successfully updated.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Attendance $attendance)
    {
        if (Auth::user()->isAbleTo('attendance delete')) {
            if ($attendance->workspace  == getActiveWorkSpace()) {

                event(new DestroyMarkAttendance($attendance));

                $attendance->delete();
                return redirect()->route('attendance.index')->with('success', __('Attendance successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BulkAttendance(Request $request)
    {
        if (Auth::user()->isAbleTo('bulk attendance manage')) {
            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            $department = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $employees = [];
            if (!empty($request->branch) && !empty($request->department)) {
                $employees = User::where('workspace_id', getActiveWorkSpace())
                    ->leftjoin('employees', 'users.id', '=', 'employees.user_id')
                    ->where('users.created_by', creatorId())->emp()
                    ->where('employees.branch_id', $request->branch)
                    ->where('employees.department_id', $request->department)
                    ->select('users.*', 'users.id as ID', 'employees.*', 'users.name as name', 'users.email as email', 'users.id as id');
                    if($request->date)
                    {
                        $employees->where('employees.dob', '<=', $request->date);
                    }
                    $employees = $employees->get();
            }
            return view('hrm::attendance.bulk', compact('employees', 'branch', 'department'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BulkAttendanceData(Request $request)
    {
        if (Auth::user()->isAbleTo('bulk attendance manage')) {
            if (!empty($request->branch) && !empty($request->department)) {
                $company_settings = getCompanyAllSetting();
                $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
                $endTime  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';
                $employees = $request->employee_id;
                $atte      = [];

                foreach ($employees as $employee) {
                    $present = 'present-' . $employee;
                    $in      = 'in-' . $employee;
                    $out     = 'out-' . $employee;
                    $atte[]  = $present;
                    if ($request->$present == 'on') {
                        $in  = date("H:i:s", strtotime($request->$in));
                        $out = date("H:i:s", strtotime($request->$out));

                        $totalLateSeconds = strtotime($in) - strtotime($startTime);
                        if ($totalLateSeconds < 0) {
                            $late = '0:00:00';
                        } else {
                            $hours = floor($totalLateSeconds / 3600);
                            $mins  = floor($totalLateSeconds / 60 % 60);
                            $secs  = floor($totalLateSeconds % 60);
                            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }

                        //early Leaving
                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($out);
                        if ($totalEarlyLeavingSeconds < 0) {
                            $earlyLeaving = '0:00:00';
                        } else {

                            $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                            $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                            $secs                     = floor($totalEarlyLeavingSeconds % 60);
                            $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }

                        if (strtotime($out) > strtotime($endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($out) - strtotime($endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }

                        $attendance = Attendance::where('employee_id', '=', $employee)->where('workspace', getActiveWorkSpace())->where('date', '=', $request->date)->first();

                        if (!empty($attendance)) {
                            $employeeAttendance = $attendance;
                        } else {
                            $employeeAttendance              = new Attendance();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = creatorId();
                            $employeeAttendance->workspace   = getActiveWorkSpace();
                        }
                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->status        = 'Present';
                        $employeeAttendance->clock_in      = $in;
                        $employeeAttendance->clock_out     = $out;
                        $employeeAttendance->late          = $late;
                        $employeeAttendance->early_leaving = ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00';
                        $employeeAttendance->overtime      = $overtime;
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();

                        event(new UpdateBulkAttendance($request, $employeeAttendance));

                    } else {
                        $attendance = Attendance::where('employee_id', '=', $employee)->where('workspace', getActiveWorkSpace())->where('date', '=', $request->date)->first();

                        if (!empty($attendance)) {
                            $employeeAttendance = $attendance;
                        } else {
                            $employeeAttendance              = new Attendance();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = creatorId();
                            $employeeAttendance->workspace     = getActiveWorkSpace();
                        }
                        $employeeAttendance->status        = 'Leave';
                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->clock_in      = '00:00:00';
                        $employeeAttendance->clock_out     = '00:00:00';
                        $employeeAttendance->late          = '00:00:00';
                        $employeeAttendance->early_leaving = '00:00:00';
                        $employeeAttendance->overtime      = '00:00:00';
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();

                        event(new UpdateBulkAttendance($request, $employeeAttendance));

                    }
                }

                return redirect()->back()->with('success', __('Employee attendance successfully created.'));
            } else {
                return redirect()->back()->with('error', __('Branch & department field required.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function attendance(Request $request)
    {
        $company_settings = getCompanyAllSetting();
        if (!empty($company_settings['ip_restrict']) && $company_settings['ip_restrict'] == 'on') {
            $userIp = request()->ip();
            $ip     = IpRestrict::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->whereIn('ip', [$userIp])->first();

            if (empty($ip)) {
                return redirect()->back()->with('error', __('This IP is not allowed to clock in & clock out.'));
            }
        }

        $employeeId      = \Auth::user()->id;
        $todayAttendance = Attendance::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->first();
        $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
        $endTime  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';

        $attendance = Attendance::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->where('clock_out', '=', '00:00:00')->first();

        if ($attendance != null) {
            $attendance            = Attendance::find($attendance->id);
            $attendance->clock_out = $endTime;
            $attendance->save();
        }

        // Find the last clocked out entry for the employee
        $lastClockOutEntry = Attendance::orderBy('id', 'desc')
            ->where('employee_id', '=', $employeeId)
            ->where('clock_out', '!=', '00:00:00')
            ->where('date', '=', date('Y-m-d'))
            ->first();

        if (!empty($company_settings['defult_timezone'])) {
            date_default_timezone_set($company_settings['defult_timezone']);
        }
        $date = date("Y-m-d");
        $time = date("H:i");

        if ($lastClockOutEntry != null) {
            $lastClockOutTime = $lastClockOutEntry->clock_out;
            $actualClockInTime = $date . ' ' . $time;

            $totalLateSeconds = strtotime($actualClockInTime) - strtotime($date . ' ' . $lastClockOutTime);

            $totalLateSeconds = max($totalLateSeconds, 0);

            $hours = floor($totalLateSeconds / 3600);
            $mins  = floor($totalLateSeconds / 60 % 60);
            $secs  = floor($totalLateSeconds % 60);
            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        } else {
            $expectedStartTime = $date . ' ' . $startTime;
            $actualClockInTime = $date . ' ' . $time;

            $totalLateSeconds = strtotime($actualClockInTime) - strtotime($expectedStartTime);

            $totalLateSeconds = max($totalLateSeconds, 0);

            $hours = floor($totalLateSeconds / 3600);
            $mins  = floor($totalLateSeconds / 60 % 60);
            $secs  = floor($totalLateSeconds % 60);
            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        }

        $checkDb = Attendance::where('employee_id', '=', $employeeId)->get()->toArray();

        if (empty($checkDb)) {
            $employeeAttendance              = new Attendance();
            $employeeAttendance->employee_id = $employeeId;
            $employeeAttendance->date          = $date;
            $employeeAttendance->status        = 'Present';
            $employeeAttendance->clock_in      = $time;
            $employeeAttendance->clock_out     = '00:00:00';
            $employeeAttendance->late          = $late;
            $employeeAttendance->early_leaving = '00:00:00';
            $employeeAttendance->overtime      = '00:00:00';
            $employeeAttendance->total_rest    = '00:00:00';
            $employeeAttendance->created_by  = creatorId();
            $employeeAttendance->workspace   = getActiveWorkSpace();
            $employeeAttendance->save();
            return redirect()->back()->with('success', __('Employee Successfully Clock In.'));
        }
        $employeeAttendance              = new Attendance();
        $employeeAttendance->employee_id = $employeeId;
        $employeeAttendance->date          = $date;
        $employeeAttendance->status        = 'Present';
        $employeeAttendance->clock_in      = $time;
        $employeeAttendance->clock_out     = '00:00:00';
        $employeeAttendance->late          = $late;
        $employeeAttendance->early_leaving = '00:00:00';
        $employeeAttendance->overtime      = '00:00:00';
        $employeeAttendance->total_rest    = '00:00:00';
        $employeeAttendance->created_by  = creatorId();
        $employeeAttendance->workspace   = getActiveWorkSpace();
        $employeeAttendance->save();
        return redirect()->back()->with('success', __('Employee Successfully Clock In.'));
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('attendance import')) {
            return view('hrm::attendance.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('attendance import')) {
            session_start();

            $error = '';

            $html = '';
            if ($request->hasFile('file')) {
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
                                            <option value="email">Employee Email</option>
                                            <option value="date">Date</option>
                                            <option value="clock_in">Clock in</option>
                                            <option value="clock_out">Clock out</option>
                                        </select>
                                </th>
                                ';
                    }
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';

                        $temp_data[] = $row;
                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {
                $error = 'Please Select File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );

            return json_encode($output);
        } else {
            $output = array(
                'error' => 'Permission denied.',
                'output' => '',
            );

            return json_encode($output);
        }
    }

    public function fileImportModal()
    {
        if (Auth::user()->isAbleTo('attendance import')) {
            return view('hrm::attendance.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function AttendanceImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('attendance import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];
            $company_settings = getCompanyAllSetting();

            foreach ($file_data as $key => $row) {
                $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
                $endTime  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';

                $employee = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->Where('email', $row[$request->email])->emp()->first();
                $attendance = null;
                if (!empty($employee)) {
                    $attendance = Attendance::where('employee_id', '=', $employee->id)->where('workspace', getActiveWorkSpace())->where('date', '=', $row[$request->date])->first();
                }
                if (empty($attendance) && !empty($employee)) {
                    try {
                        $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
                        $endTime  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';

                        $date = date("Y-m-d");
                        $totalLateSeconds = strtotime($row[$request->clock_in]) - strtotime($date . $startTime);
                        if ($totalLateSeconds < 0) {
                            $late = '0:00:00';
                        } else {
                            $hours = floor($totalLateSeconds / 3600);
                            $mins  = floor($totalLateSeconds / 60 % 60);
                            $secs  = floor($totalLateSeconds % 60);
                            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }

                        //early Leaving
                        $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($row[$request->clock_out]);
                        if ($totalEarlyLeavingSeconds < 0) {
                            $earlyLeaving = '0:00:00';
                        } else {
                            $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                            $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                            $secs                     = floor($totalEarlyLeavingSeconds % 60);
                            $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }

                        if (strtotime($row[$request->clock_out]) > strtotime($date . $endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($row[$request->clock_out]) - strtotime($date . $endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }

                        $employeeAttendance                = new Attendance();
                        $employeeAttendance->employee_id   = $employee->id;
                        $employeeAttendance->date          = $row[$request->date];
                        $employeeAttendance->status        = 'Present';
                        $employeeAttendance->clock_in      = $row[$request->clock_in] . ':00';
                        $employeeAttendance->clock_out     = $row[$request->clock_out] . ':00';
                        $employeeAttendance->late          = $late;
                        $employeeAttendance->early_leaving = $earlyLeaving;
                        $employeeAttendance->overtime      = $overtime;
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->workspace     = getActiveWorkSpace();
                        $employeeAttendance->created_by    = creatorId();
                        $employeeAttendance->save();
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->date] . '</td>';
                        $html .= '<td>' . $row[$request->clock_in] . '</td>';
                        $html .= '<td>' . $row[$request->clock_out] . '</td>';
                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';
                    $html .= '<td>' . $row[$request->email] . '</td>';
                    $html .= '<td>' . $row[$request->date] . '</td>';
                    $html .= '<td>' . $row[$request->clock_in] . '</td>';
                    $html .= '<td>' . $row[$request->clock_out] . '</td>';
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
            return response()->json([
                'html' => false,
                'response' => 'Permission denied.',
            ]);
        }
    }
}
