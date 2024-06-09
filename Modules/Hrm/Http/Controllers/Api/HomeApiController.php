<?php

namespace Modules\Hrm\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;
use Modules\Hrm\Entities\Announcement;
use Modules\Hrm\Entities\Attendance;
use Carbon\Carbon;


class HomeApiController extends Controller
{
    public function index(Request $request)
    {
		
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
			
        try{
            
            $user_id            = $request->user_id;
            $activeWorkspace    = $request->workspace_id;
            $company_settings = getCompanyAllSetting($user_id , $request->workspace_id);
            
            if (!empty($company_settings['defult_timezone'])) {
                date_default_timezone_set($company_settings['defult_timezone']);
            }
            
            $date = date("Y-m-d");
            $emp = Employee::where('user_id', '=', $user_id)->first();
            $currentMonth = Carbon::now()->format('m'); // Get the current month

            $attendance = Attendance::orderBy('id', 'desc')->where('employee_id', '=', $user_id)->where('date', '=', $date)->first();
			$currentDate = Carbon::today(); // Get current date

			$totalTime = Attendance::where('employee_id', $user_id)
				->whereDate('date', $currentDate) // Filter by current date
				->whereNotNull('clock_in') // Make sure clock_in is not null
				->where('status', 'Present') // Filter by status 'Present'
				->get()
				->sum(function ($entry) {
					// Check if clock_out is zero
					if ($entry->clock_out == '00:00:00') {
						// If clock_out is zero, use current time
						$clockOut = Carbon::now();
					} else {
						// If clock_out is not zero, parse the clock_out time
						$clockOut = Carbon::parse($entry->date . ' ' . $entry->clock_out);
					}

					// Parse clock_in time
					$clockIn = Carbon::parse($entry->date . ' ' . $entry->clock_in);

					// Calculate the time difference in minutes between clock_in and clock_out
					return $clockOut->diffInMinutes($clockIn);
				});
			
	
			// Convert total minutes to hours and minutes
			$totalHours = floor($totalTime / 60);
			$totalMinutes = $totalTime % 60;
			$totalTimeString = sprintf("%02d:%02d hours", $totalHours, $totalMinutes);

            if (!empty($emp)) {
                $announcements = Announcement::select('announcements.*')->orderBy('announcements.id', 'desc')->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $emp->id)->orWhere(
                    function ($q) use($currentMonth) {
                        $q->where('announcements.department_id', 0)
                            ->where('announcements.employee_id', 0)
                            ->whereRaw('MONTH(announcements.start_date) = ?', [$currentMonth])
                            ->orWhereRaw('MONTH(announcements.end_date) = ?', [$currentMonth]);
                    }
                )->get()
                ->map(function($announcement){
                    return [
                        "id"          => $announcement->id,
                        "title"       => $announcement->title,
                        "start_date"  => $announcement->start_date,
                        "end_date"    => $announcement->end_date,
                        "description" => $announcement->description,
                        "start_date"  => $announcement->start_date,
                        "end_date"    => $announcement->end_date,
                        "workspace"   => $announcement->workspace,
                        "created_by"  => $announcement->created_by
                    ];
                });
            } else {
                $announcements = [];
            }
			// Parse clock-in time to Carbon instance
			$clockIn = isset($attendance->clock_in) && $attendance->clock_in != '00:00:00' ? Carbon::createFromFormat('H:i:s', $attendance->clock_in) : Carbon::createFromTime(0, 0, 0);
			// Format clock-in time to AM/PM format
			$clockInFormatted = $clockIn->format('h:i A');

			// Parse clock-out time to Carbon instance
			$clockOut = isset($attendance->clock_out) && $attendance->clock_out != '00:00:00' ? Carbon::createFromFormat('H:i:s', $attendance->clock_out) : Carbon::createFromTime(0, 0, 0);
			// Format clock-out time to AM/PM format
			$clockOutFormatted = $clockOut->format('h:i A');


				
            $data = [
                'is_clockin'    => isset($attendance->clock_out) && $attendance->clock_out == '00:00:00' ? 1 : 0,
                'attendance_id' => isset($attendance->clock_out) && $attendance->clock_out == '00:00:00' ? $attendance->id : 0,
				"clock_in" 		=> isset($attendance->clock_in) && $attendance->clock_in != '00:00:00' ? $clockInFormatted : '00:00',
				"clock_out" 	=> isset($attendance->clock_out) && $attendance->clock_out != '00:00:00' ? $clockOutFormatted : '00:00',
				"total_hours" 	=> $totalTimeString,
                'announcements' => $announcements,
            ];

            return response()->json(['status' => 1 , 'data' => $data]);

        } catch (\Exception $e) {
			
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }
}




