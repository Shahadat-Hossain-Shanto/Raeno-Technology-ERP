<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftAllocation;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Weekend;
use App\Models\EmployeeLeave;

use DB;
use Log;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function create(){
        return view('attendance/attendance');
    }

    public function employeeList(){
        $todayDate = Carbon::today();
        
        $day = Carbon::parse($todayDate)->dayName;

        $data = DB::table('employee_tables')
                ->join('attendances', function($join) {
                        $join->on('employee_tables.id', '=', 'attendances.employee_id');
                    })
                // ->whereNull('attendances.employee_id')
                ->where([
                    ['attendances.attendance_date', $todayDate],
                    ['attendances.subscriber_id', Auth::user()->subscriber_id]
                ])
            ->get();

        // Log::info($day);

        if($data->isEmpty()){

            $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();

            $checkHoildays = Holiday::where('subscriber_id', Auth::user()->subscriber_id)
                ->whereDate('start_date', '<=', $todayDate)
                ->whereDate('end_date', '>=', $todayDate)
                ->first();

            $checkWeekends = Weekend::where([
                ['subscriber_id', Auth::user()->subscriber_id], 
                ['weekend_name', $day]
            ])->first();

            // $checkLeaves = DB::table('employee_tables')
            //     ->join('employee_leaves', function($join) {
            //             $join->on('employee_tables.id', '=', 'employee_leaves.employee_id');
            //         })
            //     // ->whereNull('attendances.employee_id')
            //     ->where([
            //         ['employee_leaves.subscriber_id', Auth::user()->subscriber_id]
            //     ])
            //     ->whereDate('start_date', '<=', $todayDate)
            //     ->whereDate('end_date', '>=', $todayDate)
            //     ->get();

            // Log::info($checkLeaves);

            if($checkHoildays){
                return response() -> json([
                    'status'=>200,
                    'message' => 'holiday',
                    'data' => $employees,
                    'holidayName' => $checkHoildays->holiday_name
                ]);
            }else{
                if($checkWeekends){
                    return response() -> json([
                        'status'=>200,
                        'message' => 'weekend',
                        'data' => $employees,
                        'weekendName' => 'Weekend'
                    ]);
                }else{
                    return response() -> json([
                        'status'=>200,
                        'message' => 'No data',
                        'data' => $employees,
                        'holidayName' => 'Absent'
                    ]);
                }
                
            }
        }

        return response() -> json([
            'status'=>200,
            'message' => 'Success',
            'data' => $data
        ]);
    }

    public function attendanceStatus(Request $request){
        $empId = $request->employeeId;
        $signIn = $request->signInTime.':00';
        $signOut = $request->signOutTime.':00';

        $shifts = ShiftAllocation::where('employee_id', $empId)->get();
        $today = Carbon::now()->format('Y-m-d');
        
        // Log::info($empId);
        foreach($shifts as $shift){
            if($shift->end_date >= $today){

                $sihftCheck = Shift::where('id', $shift->shift_id)->first();
                if( $sihftCheck->in_time >= $signIn ){

                    if($sihftCheck->out_time > $signOut){
                        return response() -> json([
                            'status'=>200,
                            'message' => 'Early out'
                        ]);
                    }else{
                        return response() -> json([
                            'status'=>200,
                            'message' => 'Present'
                        ]);
                    }
                    
                }else{

                    if($sihftCheck->out_time > $signOut){
                        return response() -> json([
                            'status'=>200,
                            'message' => 'Late in & Early out'
                        ]);
                    }else{
                        return response() -> json([
                            'status'=>200,
                            'message' => 'Late In'
                        ]);
                    }

                }

            }
        }

        return response() -> json([
            'status'=>200,
            'message' => 'No allocated shift',

        ]);

    }

    public function employeeListDateWise($date){
        // $data = Attendance::rightjoin('employee_tables', function($join) {
        //             $join->on('attendances.employee_id', '=', 'employee_tables.id');
        //             })
        //         ->whereNull('employee_tables.id')
        //         ->where([
        //             ['attendances.attendance_date', $date],
        //             ['attendances.subscriber_id', Auth::user()->subscriber_id]
        //         ])
        //     ->get();

         $day = Carbon::parse($date)->dayName;

        $data = DB::table('employee_tables')
                ->join('attendances', function($join) {
                        $join->on('employee_tables.id', '=', 'attendances.employee_id');
                    })
                // ->whereNull('attendances.employee_id')
                ->where([
                    ['attendances.attendance_date', $date],
                    ['attendances.subscriber_id', Auth::user()->subscriber_id]
                ])
            ->get();
        
        

        if($data->isEmpty()){

            $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();

            $checkHoildays = Holiday::where('subscriber_id', Auth::user()->subscriber_id)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            $checkWeekends = Weekend::where([
                ['subscriber_id', Auth::user()->subscriber_id], 
                ['weekend_name', $day]
            ])->first();

            $checkLeaves = DB::table('employee_tables')
            ->leftjoin('employee_leaves', function($join) {
                    $join->on('employee_tables.id', '=', 'employee_leaves.employee_id');
                })
            // ->whereNull('employee_leaves.employee_id')
            ->where([
                ['employee_leaves.subscriber_id', Auth::user()->subscriber_id]
            ])
            ->whereDate('employee_leaves.start_date', '<=', $date)
            ->whereDate('employee_leaves.end_date', '>=', $date)
            ->get();

            $employeesX = [];
            $attendance_status = 'false';
            $leave_status = '';

            foreach($employees as $employee){
                foreach($checkLeaves as $checkLeave){
                    if($employee->id == $checkLeave->employee_id){
                        $attendance_status = 'true';
                        $leave_status = $checkLeave->leave_status;
                    }
                }
                if($attendance_status != 'false' && $leave_status != ''){
                    $employeeX = [
                        'id' => $employee->id,
                        'employee_name' => $employee->employee_name,
                        'designation' => $employee->designation,
                        'department' => $employee->department,
                        'attendance_status' => $attendance_status,
                        'leave_status' => $leave_status,
                    ];

                    $employeesX[] = $employeeX;
                }else{
                    $employeeX = [
                        'id' => $employee->id,
                        'employee_name' => $employee->employee_name,
                        'designation' => $employee->designation,
                        'department' => $employee->department,
                        'attendance_status' => 'false',
                        'leave_status' => '',
                    ];

                    $employeesX[] = $employeeX;
                }
 
            }

            

            // Log::info(json_encode($employeesX));

            // Log::info($checkHoildays);

            if($checkHoildays){
                return response() -> json([
                    'status'=>200,
                    'message' => 'holiday',
                    'data' => $employees,
                    'holidayName' => $checkHoildays->holiday_name
                ]);
            }elseif($checkWeekends){
                return response() -> json([
                        'status'=>200,
                        'message' => 'weekend',
                        'data' => $employees,
                        'weekendName' => 'Weekend'
                    ]);
            }else{
                 if($checkLeaves->isEmpty()){
                    return response() -> json([
                        'status'=>200,
                        'message' => 'absent',
                        'data' => $employees,
                        'absent' => 'Absent'
                    ]);
                    
                }else{
                    return response() -> json([
                        'status'=>200,
                        'message' => 'leave',
                        'data' => $employeesX,
                    ]);
                }
            }
        }

        return response() -> json([
            'status'=>200,
            'message' => 'Success',
            'data' => $data
        ]);
    }

    public function attendanceSubmit(Request $request){

        $checkAttendance = Attendance::where([
                    ['attendance_date', $request->attendanceDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])->get();
        
        // Log::info($checkAttendance);

        if($checkAttendance->isEmpty()){
            foreach($request->attendanceList as $attendance){ 
                $newAttendance = new Attendance;
                $newAttendance->employee_name = $attendance['employee_name'];
                $newAttendance->employee_id = $attendance['employee_id'];
                $newAttendance->designation = $attendance['designation'];
                $newAttendance->department = $attendance['department'];
                $newAttendance->attendance_date = $attendance['attendance_date'];
                $newAttendance->sign_in = $attendance['sign_in'];
                $newAttendance->sign_out = $attendance['sign_out'];
                $newAttendance->stay_time = $attendance['stay_time'];
                $newAttendance->attendance_status = $attendance['status'];
                $newAttendance->subscriber_id = Auth::user()->subscriber_id;
                $newAttendance->save();
    
            }

            return response() -> json([
                'status'=>200,
                'message' => 'Attendance taken successfully!'
            ]);
        }else{
            foreach($checkAttendance as $check){
                foreach($request->attendanceList as $attendance){ 
                    if($check->employee_id == $attendance['employee_id']){
                        $id = $check->id;
                        $updateAttendance = Attendance::find($id);
                        $updateAttendance->sign_in = $attendance['sign_in'];
                        $updateAttendance->sign_out = $attendance['sign_out'];
                        $updateAttendance->stay_time = $attendance['stay_time'];
                        $updateAttendance->attendance_status = $attendance['status'];
                        $updateAttendance->save();
                    }
                }
            }
            return response() -> json([
                'status'=>200,
                'message' => 'Attendance updated successfully!'
            ]);
        }
    }
}
