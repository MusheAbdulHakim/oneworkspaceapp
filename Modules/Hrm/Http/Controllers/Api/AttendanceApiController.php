<?php

namespace Modules\Hrm\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Attendance;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WorkSpace;
use App\Models\User;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
   
    public function clockInOut(Request $request)
    {

        $validator = \Validator::make(
            $request->all(), [
                'type' => 'required|in:clockin,clockout',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status'=>0, 'message'=>$messages->first()],403);
        }

        if($request->type == 'clockin'){

            try{

                $employeeId      = $request->user_id;
                $activeWorkspace = $request->workspace_id;
                $company_settings = getCompanyAllSetting($request->user_id , $request->workspace_id);
    
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
                $time = date("H:i:s");
    
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
                    $employeeAttendance->workspace   = $activeWorkspace;
                    $employeeAttendance->save();
    
                   // Parse clock-in time to Carbon instance
					$clockIn = Carbon::createFromFormat('H:i:s',$employeeAttendance->clock_in );
					// Format clock-in time to AM/PM format
					$clockInFormatted = $clockIn->format('h:i A');

					$currentDate = Carbon::today(); // Get current date

					$totalTime = Attendance::where('employee_id', $employeeId)
						->whereDate('date', $currentDate) // Filter by current date
						->whereNotNull('clock_in') // Make sure clock_in is not null
						->whereNotNull('clock_out') // Make sure clock_out is not null
						->where('status', 'Present') // Filter by status 'Present'
						->get()
						->sum(function ($entry) {
							
							// Calculate the time difference in minutes between clock_in and clock_out
							$clockIn = Carbon::parse($entry->date . ' ' . $entry->clock_in);
							//$clockOut = Carbon::parse($entry->date . ' ' . $entry->clock_out);
							
							// Check if clock_out is zero
							if ($entry->clock_out == '00:00:00') {
								// If clock_out is zero, use current time
								$clockOut = Carbon::now();
							} else {
								// If clock_out is not zero, parse the clock_out time
								$clockOut = Carbon::parse($entry->date . ' ' . $entry->clock_out);
							}

							return $clockOut->diffInMinutes($clockIn);
						});

					// Convert total minutes to hours and minutes
					$totalHours = floor($totalTime / 60);
					$totalMinutes = $totalTime % 60;
					$totalTimeString = sprintf("%02d:%02d hours", $totalHours, $totalMinutes);

					
					$data = [
						'is_clockin'        => 1,
						'attendence_id'     => $employeeAttendance->id,
						"clock_in" 			=> $clockInFormatted,
						"total_hours" 		=> $totalTimeString,
    				];
                    return response()->json(['status' => 1 , 'message' => 'Employee Successfully Clock In.' , 'data' => $data]);
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
                $employeeAttendance->workspace   = $activeWorkspace;
                $employeeAttendance->save();
    
				// Parse clock-in time to Carbon instance
				$clockIn = Carbon::createFromFormat('H:i:s',$employeeAttendance->clock_in );
				// Format clock-in time to AM/PM format
				$clockInFormatted = $clockIn->format('h:i A');

				$currentDate = Carbon::today(); // Get current date

				$totalTime = Attendance::where('employee_id', $employeeId)
					->whereDate('date', $currentDate) // Filter by current date
					->whereNotNull('clock_in') // Make sure clock_in is not null
					->whereNotNull('clock_out') // Make sure clock_out is not null
					->where('status', 'Present') // Filter by status 'Present'
					->get()
					->sum(function ($entry) {
						// Calculate the time difference in minutes between clock_in and clock_out
						$clockIn = Carbon::parse($entry->date . ' ' . $entry->clock_in);
						//$clockOut = Carbon::parse($entry->date . ' ' . $entry->clock_out);
						
						// Check if clock_out is zero
						if ($entry->clock_out == '00:00:00') {
							// If clock_out is zero, use current time
							$clockOut = Carbon::now();
						} else {
							// If clock_out is not zero, parse the clock_out time
							$clockOut = Carbon::parse($entry->date . ' ' . $entry->clock_out);
						}
						
						return $clockOut->diffInMinutes($clockIn);
					});

				// Convert total minutes to hours and minutes
				$totalHours = floor($totalTime / 60);
				$totalMinutes = $totalTime % 60;
				$totalTimeString = sprintf("%02d:%02d hours", $totalHours, $totalMinutes);
				
                $data = [
                    'is_clockin'        => 1,
                    'attendence_id'     => $employeeAttendance->id,
					"clock_in" 			=> $clockInFormatted,
					"total_hours" 		=> $totalTimeString,
                ];
    
                return response()->json(['status' => 1 , 'message' => 'Employee Successfully Clock In.' , 'data' => $data]);
    
            } catch (\Exception $e) {
				
                return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
            } 

        }else{

            $validator = \Validator::make(
                $request->all(), [
                    'attendence_id' => 'required',
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status'=>0, 'message'=>$messages->first()],403);
            }
    
            try{
                $employeeId         = $request->user_id;
                $activeWorkspace    = $request->workspace_id;
                $company_settings   = getCompanyAllSetting($request->user_id , $request->workspace_id);
    
                $todayAttendance = Attendance::where('employee_id', '=', $employeeId)->where('workspace', $activeWorkspace)->where('date', '=', date('Y-m-d'))->first();
                
                $startTime  = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
                $endTime    = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';
    
                if (!empty($company_settings['defult_timezone'])) {
                    date_default_timezone_set($company_settings['defult_timezone']);
                }
    
                $date = date("Y-m-d");
                $time = date("H:i:s");
    
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
    
                $attendance                = Attendance::find($request->attendence_id);
                $attendance->clock_out     = $time;
                $attendance->early_leaving = $earlyLeaving;
                $attendance->overtime      = $overtime;
                $attendance->save();
    
				
				// Parse clock-in time to Carbon instance
				$clockIn = Carbon::createFromFormat('H:i:s',$attendance->clock_in );
				// Format clock-in time to AM/PM format
				$clockInFormatted = $clockIn->format('h:i A');
				
				// Parse clock-in time to Carbon instance
				$clockOut = Carbon::createFromFormat('H:i:s',$attendance->clock_out );
				// Format clock-in time to AM/PM format
				$clockOutFormatted = $clockOut->format('h:i A');

				$currentDate = Carbon::today(); // Get current date

				$totalTime = Attendance::where('employee_id', $employeeId)
					->whereDate('date', $currentDate) // Filter by current date
					->whereNotNull('clock_in') // Make sure clock_in is not null
					->whereNotNull('clock_out') // Make sure clock_out is not null
					->where('status', 'Present') // Filter by status 'Present'
					->get()
					->sum(function ($entry) {
						// Calculate the time difference in minutes between clock_in and clock_out
						$clockIn = Carbon::parse($entry->date . ' ' . $entry->clock_in);
						$clockOut = Carbon::parse($entry->date . ' ' . $entry->clock_out);
						return $clockOut->diffInMinutes($clockIn);
					});
				
				// Convert total minutes to hours and minutes
				$totalHours = floor($totalTime / 60);
				$totalMinutes = $totalTime % 60;
				$totalTimeString = sprintf("%02d:%02d hours", $totalHours, $totalMinutes);

                $data = [
                    'is_clockin'        	=> 0,
                    'attendence_id'     	=> $attendance->id,
					'clock_in'   			=> $clockInFormatted,
					'clock_out'  			=> $clockOutFormatted,
					"total_hours" 			=> $totalTimeString,
                ];
    
                return response()->json(['status' => 1 , 'message' => 'Employee Successfully Clock Out.' , 'data' => $data]);
    
            } catch (\Exception $e) {
				
                return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
            } 
        }
    }
	
	public function attendenceHistory(Request $request)
    {
        try {
			
			$attendances = Attendance::where('employee_id', $request->user_id)->where('workspace', $request->workspace_id)->select('date', 'id', 'status', 'clock_in', 'clock_out');
				
			if ($request->type == 'monthly' && !empty($request->month)) {
                $month = $request->month;
                $year = !empty($request->year) ? $request->year : date('Y'); 

                $start_date = date("$year-$month-01");
                $end_date   = date("$year-$month-t");

                $attendances->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );

            } 
			else {
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
			
			$formattedData = [];

			foreach ($attendances->get() as $key => $attendance) {
				$date = $attendance->date;

				// Calculate total time
				$clockIn 			= strtotime($attendance->clock_in);
				$clockOut 			= strtotime($attendance->clock_out);
				$totalTimeMinutes 	= floor(($clockOut - $clockIn) / 60);
				$totalTimeHours 	= floor($totalTimeMinutes / 60);
				$totalMinutes 		= $totalTimeMinutes % 60;
				$totalTimeString 	= sprintf("%02d:%02d hours", $totalTimeHours, $totalMinutes);

				// Prepare attendance details
				$attendanceDetail = [
					'id' => $attendance->id,
					'status' => $attendance->status,
					'clock_in' => $attendance->clock_in,
					'clock_out' => $attendance->clock_out,
					'total' => $totalTimeString
				];

				// Check if the date exists in formattedData
				if (!isset($formattedData[$date])) {
					$formattedData[$date] = [
						'total_time' => '00:00 hours', // Initialize total_time
						'date' => $date, // Initialize total_time
						'history' => [], // Initialize details array
					];
				}

				// Add attendance detail
				$formattedData[$date]['history'][] = $attendanceDetail;
			}
			
			foreach($formattedData as $key => $data) {
				
				$totalTime = Attendance::where('employee_id', $request->user_id)
					->whereDate('date', $data['date']) // Filter by current date
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
				$totalTimeString = sprintf("%02d:%02d", $totalHours, $totalMinutes);

				$formattedData[$key]['total_time'] = $totalTimeString;
			}
			
			$newData = [];
			$i= 0;
			
			foreach ($formattedData as $key => $value) {
				$newKey = preg_replace('/\d{4}-\d{2}-\d{2}/', '', $i); // Remove date from the key
				$newData[$newKey] = $value;
				$i++ ;
			}

			
			return response()->json(['status' => 1,  'data' => $newData]);

		} catch (\Exception $e) {
			return response()->json(['status' => 0, 'message' => 'Something went wrong!!!']);
		}
	}

    // public function ___attendenceHistory(Request $request)
    // {
		
    //     try{
    //         $attendances = Attendance::where('employee_id', $request->user_id)->where('workspace', $request->workspace_id);

    //         if ($request->type == 'monthly' && !empty($request->month)) {
    //             $month = $request->month;
    //             $year = !empty($request->year) ? $request->year : date('Y'); 

    //             $start_date = date("$year-$month-01");
    //             $end_date   = date("$year-$month-t");

    //             $attendances->whereBetween(
    //                 'date',
    //                 [
    //                     $start_date,
    //                     $end_date,
    //                 ]
    //             );

    //         } 
	// 		//elseif ($request->type == 'daily' && !empty($request->date)) {
    //          //   $attendances->where('date', $request->date);
    //         //} 
	// 		else {
    //             $month      = date('m');
    //             $year       = date('Y');
    //             $start_date = date($year . '-' . $month . '-01');
    //             $end_date   = date($year . '-' . $month . '-t');

    //             $attendances->whereBetween(
    //                 'date',
    //                 [
    //                     $start_date,
    //                     $end_date,
    //                 ]
    //             );
    //         }

    //         $attendances = $attendances->limit(10)->offset((($request->page??1)-1)*10)->get()
    //                         ->map(function($attendance){
    //                             return [
    //                                 'id'            => $attendance->id,
    //                                 'employee_id'   => $attendance->employee_id,
    //                                 'date'          => $attendance->date,
    //                                 'status'        => $attendance->status,
    //                                 'clock_in'      => $attendance->clock_in,
    //                                 'clock_out'     => $attendance->clock_out,
    //                                 'late'          => $attendance->late,
    //                                 'early_leaving' => $attendance->early_leaving,
    //                                 'overtime'      => $attendance->overtime,
    //                                 'total_rest'    => $attendance->total_rest,
    //                                 'workspace'     => $attendance->workspace,
    //                                 'created_by'    => $attendance->created_by,
    //                             ];
    //                         });

    //         return response()->json(['status'=>1,'message'=>'','data'=>$attendances]);

    //     } catch (\Exception $e) {
			
    //         return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
    //     } 
    // }
}




