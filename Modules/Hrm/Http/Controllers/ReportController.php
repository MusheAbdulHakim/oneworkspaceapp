<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Attendance;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;
use Modules\Hrm\Entities\PaySlip;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function monthlyAttendance(Request $request)
    {
        if (Auth::user()->isAbleTo('attendance report manage')) {
            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $branch->prepend('All', '');

            $department = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $department->prepend('All', '');


            $data['branch']     = __('All');
            $data['department'] = __('All');

            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftjoin('employees', 'users.id', '=', 'employees.user_id')
                ->where('users.created_by', creatorId())->emp()
                ->select('users.id', 'users.name');
            if (!empty($request->branch)) {
                $employees->where('branch_id', $request->branch);
            }

            if (!empty($request->department)) {
                $employees->where('department_id', $request->department);
            }
            if (!empty($request->employee_id && !in_array('0', $request->employee_id))) {
                $employees->whereIn('employees.id', $request->employee_id);
            }
            $employees = $employees->get()->pluck('name', 'id');

            if ($request->has('week') && $request->type == 'weekly') {
                $week = $request->input('week');
                $year = substr($week, 0, 4);
                $week_number = substr($week, -2);

                $start_date = date("Y-m-d", strtotime($year . "W" . $week_number));
                $week_dates = [];

                $date = new DateTime($start_date);
                for ($i = 0; $i < 7; $i++) {
                    $week_dates[] = $date->format('d-m-Y');
                    $dates[] = date('d', strtotime($date->format('Y-m-d')));
                    $date->modify('+1 day');
                }
                $start_date = reset($week_dates);
                $end_date = end($week_dates);
                $curMonth    = $start_date . __(' To ') . $end_date;
            } elseif ($request->has('month') && $request->type == 'monthly') {
                $currentdate = strtotime($request->month);
                $month       = date('m', $currentdate);
                $year        = date('Y', $currentdate);

                $curMonth    = date('M-Y', strtotime($request->month));

                $num_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
                for ($i = 1; $i <= $num_of_days; $i++) {
                    $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                }
            } else {
                $month    = date('m');
                $year     = date('Y');
                $curMonth = date('M-Y', strtotime($year . '-' . $month));

                $num_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
                for ($i = 1; $i <= $num_of_days; $i++) {
                    $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                }
            }

            $employeesAttendance = [];
            $totalPresent        = $totalLeave = $totalEarlyLeave = 0;
            $ovetimeHours        = $overtimeMins = $earlyleaveHours = $earlyleaveMins = $lateHours = $lateMins = 0;
            foreach ($employees as $id => $employee) {
                $attendances['name'] = $employee;
                if ($request->type == 'weekly') {
                    foreach ($week_dates as $date) {
                        $employeeAttendance = Attendance::where('employee_id', $id)
                            ->whereDate('date', '=', date('Y-m-d', strtotime($date)))
                            ->where('workspace', getActiveWorkSpace())
                            ->first();

                        if (!empty($employeeAttendance) && $employeeAttendance->status == 'Present') {
                            $attendanceStatus[$date] = 'P';
                            $totalPresent            += 1;

                            if ($employeeAttendance->overtime > 0) {
                                $ovetimeHours += date('h', strtotime($employeeAttendance->overtime));
                                $overtimeMins += date('i', strtotime($employeeAttendance->overtime));
                            }

                            if ($employeeAttendance->early_leaving > 0) {
                                $earlyleaveHours += date('h', strtotime($employeeAttendance->early_leaving));
                                $earlyleaveMins  += date('i', strtotime($employeeAttendance->early_leaving));
                            }

                            if ($employeeAttendance->late > 0) {
                                $lateHours += date('h', strtotime($employeeAttendance->late));
                                $lateMins  += date('i', strtotime($employeeAttendance->late));
                            }
                        } elseif (!empty($employeeAttendance) && $employeeAttendance->status == 'Leave') {
                            $attendanceStatus[$date] = 'A';
                            $totalLeave              += 1;
                        } else {
                            $attendanceStatus[$date] = '';
                        }
                    }
                } else {
                    foreach ($dates as $date) {
                        $dateFormat = $year . '-' . $month . '-' . $date;
                        if ($dateFormat <= date('Y-m-d')) {
                            $employeeAttendance = Attendance::where('employee_id', $id)->where('date', $dateFormat)->where('workspace', getActiveWorkSpace())->first();

                            if (!empty($employeeAttendance) && $employeeAttendance->status == 'Present') {
                                $attendanceStatus[$date] = 'P';
                                $totalPresent            += 1;

                                if ($employeeAttendance->overtime > 0) {
                                    $ovetimeHours += date('h', strtotime($employeeAttendance->overtime));
                                    $overtimeMins += date('i', strtotime($employeeAttendance->overtime));
                                }

                                if ($employeeAttendance->early_leaving > 0) {
                                    $earlyleaveHours += date('h', strtotime($employeeAttendance->early_leaving));
                                    $earlyleaveMins  += date('i', strtotime($employeeAttendance->early_leaving));
                                }

                                if ($employeeAttendance->late > 0) {
                                    $lateHours += date('h', strtotime($employeeAttendance->late));
                                    $lateMins  += date('i', strtotime($employeeAttendance->late));
                                }
                            } elseif (!empty($employeeAttendance) && $employeeAttendance->status == 'Leave') {
                                $attendanceStatus[$date] = 'A';
                                $totalLeave              += 1;
                            } else {
                                $attendanceStatus[$date] = '';
                            }
                        } else {
                            $attendanceStatus[$date] = '';
                        }
                    }
                }

                $attendances['status'] = $attendanceStatus;
                $employeesAttendance[] = $attendances;
            }

            $totalOverTime   = $ovetimeHours + ($overtimeMins / 60);
            $totalEarlyleave = $earlyleaveHours + ($earlyleaveMins / 60);
            $totalLate       = $lateHours + ($lateMins / 60);

            $data['totalOvertime']   = $totalOverTime;
            $data['totalEarlyLeave'] = $totalEarlyleave;
            $data['totalLate']       = $totalLate;
            $data['totalPresent']    = $totalPresent;
            $data['totalLeave']      = $totalLeave;
            $data['curMonth']        = $curMonth;

            return view('hrm::report.monthlyAttendance', compact('employeesAttendance', 'branch', 'department', 'dates', 'data'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function leave(Request $request)
    {
        if (Auth::user()->isAbleTo('leave report manage')) {

            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $branch->prepend('All', '');

            $department = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $department->prepend('All', '');

            $filterYear['branch']        = __('All');
            $filterYear['department']    = __('All');
            $filterYear['type']          = __('Monthly');
            $filterYear['dateYearRange'] = date('M-Y');

            $employees = User::where('workspace_id', getActiveWorkSpace())
                ->leftjoin('employees', 'users.id', '=', 'employees.user_id')
                ->where('users.created_by', creatorId())->emp()
                ->select('users.id', 'users.name', 'employees.employee_id');

            if (!empty($request->branch)) {
                $employees->where('branch_id', $request->branch);
                $filterYear['branch'] = !empty(Branch::find($request->branch)) ? Branch::find($request->branch)->name : '';
            }
            if (!empty($request->department)) {
                $employees->where('department_id', $request->department);
                $filterYear['department'] = !empty(Department::find($request->department)) ? Department::find($request->department)->name : '';
            }


            $employees = $employees->get();

            $leaves        = [];
            $totalApproved = $totalReject = $totalPending = 0;
            foreach ($employees as $employee) {
                $employeeLeave['id']          = $employee->id;
                $employeeLeave['employee_id'] = $employee->employee_id;
                $employeeLeave['employee']    = $employee->name;

                $approved = Leave::where('user_id', $employee->id)->where('status', 'Approved');
                $reject   = Leave::where('user_id', $employee->id)->where('status', 'Reject');
                $pending  = Leave::where('user_id', $employee->id)->where('status', 'Pending');

                if ($request->type == 'monthly' && !empty($request->month)) {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $approved->whereMonth('applied_on', $month)->whereYear('applied_on', $year);
                    $reject->whereMonth('applied_on', $month)->whereYear('applied_on', $year);
                    $pending->whereMonth('applied_on', $month)->whereYear('applied_on', $year);

                    $filterYear['dateYearRange'] = date('M-Y', strtotime($request->month));
                    $filterYear['type']          = __('Monthly');
                } elseif (!isset($request->type)) {
                    $month     = date('m');
                    $year      = date('Y');
                    $monthYear = date('Y-m');

                    $approved->whereMonth('applied_on', $month)->whereYear('applied_on', $year);
                    $reject->whereMonth('applied_on', $month)->whereYear('applied_on', $year);
                    $pending->whereMonth('applied_on', $month)->whereYear('applied_on', $year);

                    $filterYear['dateYearRange'] = date('M-Y', strtotime($monthYear));
                    $filterYear['type']          = __('Monthly');
                }

                if ($request->type == 'yearly' && !empty($request->year)) {
                    $approved->whereYear('applied_on', $request->year);
                    $reject->whereYear('applied_on', $request->year);
                    $pending->whereYear('applied_on', $request->year);


                    $filterYear['dateYearRange'] = $request->year;
                    $filterYear['type']          = __('Yearly');
                }

                $approved = $approved->count();
                $reject   = $reject->count();
                $pending  = $pending->count();

                $totalApproved += $approved;
                $totalReject   += $reject;
                $totalPending  += $pending;

                $employeeLeave['approved'] = $approved;
                $employeeLeave['reject']   = $reject;
                $employeeLeave['pending']  = $pending;


                $leaves[] = $employeeLeave;
            }

            $starting_year = date('Y', strtotime('-5 year'));
            $ending_year   = date('Y', strtotime('+5 year'));

            $filterYear['starting_year'] = $starting_year;
            $filterYear['ending_year']   = $ending_year;

            $filter['totalApproved'] = $totalApproved;
            $filter['totalReject']   = $totalReject;
            $filter['totalPending']  = $totalPending;


            return view('hrm::report.leave', compact('department', 'branch', 'leaves', 'filterYear', 'filter'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function employeeLeave(Request $request, $employee_id, $status, $type, $month, $year)
    {
        if (Auth::user()->isAbleTo('leave report manage')) {
            $leaveTypes = LeaveType::where('workspace', getActiveWorkSpace())->get();

            $leaves     = [];
            foreach ($leaveTypes as $leaveType) {
                $leave        = new Leave();
                $leave->title = $leaveType->title;
                $totalLeave   = Leave::where('user_id', '=', $employee_id)->where('status', $status)->where('workspace', getActiveWorkSpace())->where('leave_type_id', $leaveType->id);
                if ($type == 'yearly') {
                    $totalLeave->whereYear('applied_on', $year);
                } else {
                    $m = date('m', strtotime($month));
                    $y = date('Y', strtotime($month));

                    $totalLeave->whereMonth('applied_on', $m)->whereYear('applied_on', $y);
                }
                $totalLeave = $totalLeave->get()->count();
                $leave->total = $totalLeave;
                $leaves[]     = $leave;
            }
            $leaveData = Leave::where('user_id', '=', $employee_id)->where('status', $status)->where('workspace', getActiveWorkSpace());
            if ($type == 'yearly') {
                $leaveData->whereYear('applied_on', $year);
            } else {
                $m = date('m', strtotime($month));
                $y = date('Y', strtotime($month));

                $leaveData->whereMonth('applied_on', $m)->whereYear('applied_on', $y);
            }
            $leaveData = $leaveData->get();

            return view('hrm::report.leaveShow', compact('leaves', 'leaveData'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function Payroll(Request $request)
    {
        if (Auth::user()->isAbleTo('payroll report manage')) {
            $count_key = 0;
            $data = [];
            if (!empty($request->all()) && !empty($request->start_month) && !empty($request->end_month) && !empty($request->report_type) && !empty($request->employees)) {
                $selected_month = [];

                $start    = new \DateTime($request->start_month);
                $start->modify('first day of this month');
                $end      = new \DateTime($request->end_month);
                $end->modify('first day of next month');
                $interval = \DateInterval::createFromDateString('1 month');
                $period   = new \DatePeriod($start, $interval, $end);

                // Selected Months Get and set header
                $report_type = !empty($request->report_type) ? $request->report_type : 'allowance';
                $header_args = [];
                $header_args[] = 'Name';

                foreach ($period as $dt) {
                    $selected_month[] =  $dt->format("Y-m");
                    $header_args[] =  $dt->format("M-Y");
                }
                $header_args[] = 'Total';

                // Get  selected Employees
                $employees = Employee::where('workspace', getActiveWorkSpace());
                if (isset($request->employees) && !in_array('0', $request->employees)) {
                    $employees = $employees->whereIn('id', $request->employees);
                }
                $employees = $employees->get();

                // calculation
                foreach ($employees as $index => $employee) {
                    $temp_data = [];
                    $temp_data[] = $employee->name;

                    $month_calculation = Employee::PayrollCalculation($employee->id, $selected_month, $report_type);

                    $temp_data =  array_merge($temp_data, $month_calculation);

                    array_push($data, $temp_data);

                    $count_key = count($month_calculation);
                }
            }

            if (empty($request->all()) || $request->is_export == 'no' || !empty($request->all())) {
                $employees_box = [];
                $report_type = [
                    '' => 'Please Select',
                    'allowance' => 'Allowance',
                    'commission' => 'Commission',
                    'loan' => 'Loan',
                    'saturation_deduction' => 'Saturation Deduction',
                    'other_payment' => 'Other Payment',
                    'overtime' => 'Overtime',
                ];

                if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                    $employees = Employee::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
                } else {
                    if (!empty($request->all())) {
                        $employees = Employee::select('employees.*', 'employees.name')
                            ->leftJoin('pay_slips', 'employees.id', '=', 'pay_slips.employee_id')
                            ->where('pay_slips.created_by', creatorId())
                            ->where('pay_slips.salary_month', '>=', $request->start_month)
                            ->where('pay_slips.salary_month', '<=', $request->end_month);
                    } else {
                        $employees = Employee::where('workspace', getActiveWorkSpace());
                    }
                    $employees_box = $employees->pluck('name', 'employees.id');

                    if (isset($request->employees) && !in_array('0', $request->employees)) {
                        $employees = $employees->whereIn('employees.id', $request->employees);
                    }
                    $employees = $employees->get();
                }

                return view('hrm::report.payroll', compact('employees', 'employees_box', 'report_type', 'data'));
            }
            if (!empty($request->all()) && $request->is_export == 'yes') {
                // For Final Total
                $final_total = [];
                $final_total[] = 'Total';
                for ($i = 1; $i <= $count_key; $i++) {
                    $final_total[] = array_sum(array_map(fn ($item) => $item[$i], $data));
                }
                array_push($data, $final_total);

                $filename = $report_type . "-" . date('Ymd') . ".csv";
                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=\"$filename\"");


                $output = fopen('php://output', 'w');
                ob_end_clean();
                fputcsv($output, $header_args);
                foreach ($data as $data_item) {
                    fputcsv($output, $data_item);
                }
                exit;
                return redirect()->route('report.payroll');
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
    public function getdepartment(Request $request)
    {

        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function getemployee(Request $request)
    {
        $employees = [];
        if (isset($request->department_id)) {

            if (!$request->department_id) {
                $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
            } else {

                $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
            }
        }

        return response()->json($employees);
    }
}
