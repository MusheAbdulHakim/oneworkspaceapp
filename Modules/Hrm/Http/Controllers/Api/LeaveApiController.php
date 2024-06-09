<?php

namespace Modules\Hrm\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WorkSpace;
use App\Models\User;

class LeaveApiController extends Controller
{
    public function index(Request $request)
    {
        try{

            $leaves   = Leave::where('user_id', '=', $request->user_id)
                                ->where('workspace', $request->workspace_id)
                                ->orderBy('id', 'desc')
                                ->get()
                                ->map(function($leave){
                                    return [
                                        "id"               => $leave->id,
                                        "employee_id"      => $leave->employee_id,
                                        "user_id"          => $leave->user_id,
                                        "leave_type_id"    => $leave->leave_type_id,
                                        "applied_on"       => $leave->applied_on,
                                        "start_date"       => $leave->start_date,
                                        "end_date"         => $leave->end_date,
                                        "total_leave_days" => $leave->total_leave_days,
                                        "leave_reason"     => $leave->leave_reason,
                                        "remark"           => $leave->remark,
                                        "status"           => $leave->status,
                                        "workspace"        => $leave->workspace,
                                        "created_by"       => $leave->created_by
                                    ];
                                });

            return response()->json(['status'=>1,'message'=>'','data'=>$leaves]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    public function store(Request $request)
    {
        try{
            $validator = \Validator::make(
                $request->all(), [
                    'leave_type_id' => 'required',
                    'start_date' => 'required|after:yesterday',
                    'end_date' => 'required',
                    'leave_reason' => 'required',
                    'remark' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['status'=>0, 'message'=>$messages->first()],403);
            }

            $user           = User::find($request->user_id);
            $leave_type     = LeaveType::find($request->leave_type_id);
            $startDate      = new \DateTime($request->start_date);
            $endDate        = new \DateTime($request->end_date);
            $endDate->add(new \DateInterval('P1D'));

            $leave    = new Leave();
            if (in_array($user->type, $user->not_emp_type)) {
                $employee           = Employee::where('id', '=', $request->employee_id)->first();
                $leave->employee_id = $request->employee_id;
                $leave->user_id     = $employee->user_id;
            } else {
                $employee = Employee::where('user_id', '=', $user->id)->first();
                if (!empty($employee)) {
                    $leave->user_id     = $user->id;
                    $leave->employee_id = $employee->id;
                } else {
                    return response()->json(['status'=>0, 'message'=>'Apologies, the employee data is currently unavailable. Please provide the necessary employee details.'],403);
                }
            }

            $date = AnnualLeaveCycle();

            // Leave day
            $leaves_used   = Leave::where('employee_id', '=', $leave->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');

            $leaves_pending  = Leave::where('employee_id', '=', $leave->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Pending')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');

            $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;

            $return = $leave_type->days - $leaves_used;
            if ($total_leave_days > $return) {
                return response()->json(['status'=>0, 'message'=>'You are not eligible for leave'],403);
            }
            if (!empty($leaves_pending) && $leaves_pending + $total_leave_days > $return) {
                return response()->json(['status'=>0, 'message'=>'Multiple leave entry is pending'],403);
            }

            if ($leave_type->days >= $total_leave_days) {

                $leave->leave_type_id    = $request->leave_type_id;
                $leave->applied_on       = date('Y-m-d');
                $leave->start_date       = $request->start_date;
                $leave->end_date         = $request->end_date;
                $leave->total_leave_days = $total_leave_days;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = $request->remark;
                $leave->status           = 'Pending';
                $leave->workspace        = $user->active_workspace;
                $leave->created_by       = $user->created_by;
                $leave->save();

                return response()->json(['status'=>1, 'data'=>$leave,'message'=>'Leave  successfully created.'],200);
            } else {
                return response()->json([
                    'status' => 1, 
                    'message' => "Leave type $leave_type->name provides a maximum of $leave_type->days days. Please make sure your selected days are under $leave_type->days days."
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

}
