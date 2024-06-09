<?php

namespace Modules\Hrm\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WorkSpace;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;

class LeaveTypeApiController extends Controller
{
    public function index(Request $request)
    {
        try{

            $leavetypes   = LeaveType::where('workspace', $request->workspace_id)
                                ->orderBy('id', 'desc')
                                ->get()
                                ->map(function($leaveType) use ($request) {

                                    $totalLeaves = Leave::where('leave_type_id',$leaveType->id)
                                                        ->where('user_id',$request->user_id)
                                                        ->where('status', 'Approved')
                                                        ->sum('total_leave_days');

                                    $is_disable = $totalLeaves < $leaveType->days ? 0 : 1 ;   

                                    return [
                                        "id"            => $leaveType->id,
                                        "title"         => $leaveType->title,
                                        "days"          => $leaveType->days,
                                        "used"          => $totalLeaves,
                                        "is_disable"    => $is_disable,
                                    ];
                                });

            return response()->json(['status' => 1 , 'message' => '' , 'data' => $leavetypes]);

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message'=>'something went wrong!!!']);
        } 
    }
}







