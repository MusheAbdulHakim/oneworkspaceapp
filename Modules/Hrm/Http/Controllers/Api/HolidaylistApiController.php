<?php

namespace Modules\Hrm\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\WorkSpace;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class HolidaylistApiController extends Controller
{

    public function index(Request $request)
    {
        try{
            $validator = \Validator::make(
                $request->all(), [
                    'workspace_id' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['status'=>0, 'message'=>$messages->first()],403);
            }

            $user = \Auth::user();
            $events = [];
            $holidays = \Modules\Hrm\Entities\Holiday::where('workspace', $request->workspace_id);
            if (!empty($request->date)) {
                $date_range = explode(' to ', $request->date);
                $holidays->where('start_date', '>=', $date_range[0]);
                $holidays->where('end_date', '<=', $date_range[1]);
            }
            $holidays = $holidays->get();
            foreach ($holidays as $key => $holiday) {
                $data = [
                    'title' => $holiday->occasion,
                    'start' => $holiday->start_date,
                    'end' => $holiday->end_date,
                    'className' => 'event-danger'
                ];
                array_push($events, $data);
            }

            return response()->json(['status'=>1,'message'=>'','data'=>$events]);
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        }
    }

}
