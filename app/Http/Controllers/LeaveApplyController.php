<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftAllocation;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Weekend;
use App\Models\EmployeeLeave;
use App\Models\LeaveType;



use DB;
use Log;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveApplyController extends Controller
{
    public function create(){
        
        $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();
        $leaveTypes = DB::table('leave_types')->where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('leave/leave-apply', ['employees' => $employees, 'leaveTypes' => $leaveTypes]);
    }

    public function store(Request $request){
        
        $messages = [
            'employee.required'  =>    "Employee name is required.",
            'leavetype.required'  =>    "Leave type is required.",
            'startdate.required'  =>    "Start date is required.",
            'enddate.required'  =>    "End date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'employee' => 'required',
            'leavetype' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
           
        ], $messages);

        if ($validator->passes()) {
            $employeeLeave = new EmployeeLeave;

            $employeeLeave->employee_id = $request->employee;
            $employeeLeave->employee_name = $request->employeename;
            $employeeLeave->leave_type_id = $request->leavetype;
            $employeeLeave->leave_type = $request->leavetypename;
            $employeeLeave->start_date = $request->startdate;
            $employeeLeave->end_date = $request->enddate;
            $employeeLeave->total_days = $request->daycount;
            $employeeLeave->note = $request->note;
            $employeeLeave->leave_status = 'pending';
            $employeeLeave->subscriber_id = Auth::user()->subscriber_id;
            $employeeLeave->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Leave created Successfully!'
            ]);
        }
       return response()->json(['error'=>$validator->errors()]);  
        //return redirect('leave-apply-list')->with('status', 'added successfully!');      


    }

    public function listView(){
        return view('leave/leave-apply-list');
    }

    public function list(Request $request){
        $leave = EmployeeLeave::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'leave'=>$leave,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $leave = EmployeeLeave::find($id);

        if($leave){
            return response()->json([
                'status'=>200,
                'leave'=>$leave,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'startdate.required'  =>    "Start date is required.",
            'enddate.required'  =>    "End date is required.",
            'status.required'  =>    "Status is required.",
        ];

        $validator = Validator::make($request->all(), [
            'startdate' => 'required',
            'enddate' => 'required',
            'status' => 'required',
           
        ], $messages);

        if ($validator->passes()) {
            $leave = EmployeeLeave::find($id);

            $leave->start_date = $request->startdate;
            $leave->end_date = $request->enddate;
            $leave->leave_status = $request->status;
            $leave->note = $request->note;
            
            $leave->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Leave updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function leaveType($id){
        $leaveType = LeaveType::where('id', $id)->first();
        return response()->json(['leaveType'=>$leaveType]);

    }

    public function leaveCheckWithoutHoliday(Request $request){

        $startDate = $request->startDate;
        $endDate = $request->endDate;



        $holidays = Holiday::where('subscriber_id', Auth::user()->subscriber_id)->get(); 
        $weekends = Weekend::where('subscriber_id', Auth::user()->subscriber_id)->get(); 

        $fromDate = new Carbon($startDate);
        $toDate = new Carbon($endDate);

        $totalCount = 0;
        foreach($weekends as $weekend){
            // Get the first Friday in the date range
            $day = strtoupper($weekend->weekend_name);
            $date = $fromDate->dayOfWeek == Carbon::FRIDAY
                ? $fromDate
                : $fromDate->copy()->modify('next '.$weekend->weekend_name);


            $dates = [];

            // Iterate until you have reached the end date adding a week each time
            while ($date->lt($toDate)) {
                $dates[] = $date->toDateString();
                $date->addWeek();
            }
            
            $totalCount = $totalCount + count($dates);
            // Log::info($dates);
            // Log::info(count($dates));
        }
        // Log::info($totalCount);
        

        foreach($holidays as $holiday){
            if($startDate >= $holiday->start_date && $endDate <= $holiday->end_date){
                return response() -> json([
                    'status'=>200,
                    'message' => 'Between holiday.',
                    'data' => ''
                ]);
            }elseif($startDate >= $holiday->start_date && $endDate > $holiday->end_date && $startDate > $holiday->end_date){

                $count = $this->countDays($startDate, $endDate);
                
                return response() -> json([
                    'status'=>200,
                    'message' => 'No holiday!!!After',
                    'data' => ($count - $totalCount) + 1
                ]);
            }
            elseif($startDate < $holiday->start_date &&  $endDate <= $holiday->end_date && $endDate < $holiday->start_date){
                $count = $this->countDays($startDate, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'No holiday!!!Before',
                    'data' => ($count - $totalCount) + 1
                ]);
            }
            elseif($startDate >= $holiday->start_date && $endDate > $holiday->end_date){
                $count = $this->countDays($holiday->end_date, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'After the holiday end date.',
                    'data' => $count 
                ]);

            }elseif($startDate < $holiday->start_date &&  $endDate <= $holiday->end_date){
                $count = $this->countDays($startDate, $holiday->start_date);
                return response() -> json([
                    'status'=>200,
                    'message' => 'Before the holiday start date.',
                    'data' => $count 
                ]);
            }elseif($startDate < $holiday->start_date && $endDate > $holiday->end_date){
                $countStart = $this->countDays($startDate, $holiday->start_date);
                $countEnd = $this->countDays($holiday->end_date, $endDate);
                $totalCount = $countStart + $countEnd;
                return response() -> json([
                    'status'=>200,
                    'message' => 'Before the holiday start date and after the holiday end date.',
                    'data' => $totalCount 
                ]);
            }else{
                $count = $this->countDays($startDate, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'No holiday.',
                    'data' => $count 
                ]);
            }
        }

        return response() -> json([
            'status'=>200,
            'message' => 'Success'
        ]);
    }

    function countDays($date1, $date2){
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400))+1;
    }

    public function leaveCheckWithHoliday(Request $request){
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $holidays = Holiday::where('subscriber_id', Auth::user()->subscriber_id)->get(); 
        $weekends = Weekend::where('subscriber_id', Auth::user()->subscriber_id)->get(); 

        $fromDate = new Carbon($startDate);
        $toDate = new Carbon($endDate);

        $totalCount = 0;
        foreach($weekends as $weekend){
            // Get the first Friday in the date range
            $day = strtoupper($weekend->weekend_name);
            $date = $fromDate->dayOfWeek == Carbon::FRIDAY
                ? $fromDate
                : $fromDate->copy()->modify('next '.$weekend->weekend_name);


            $dates = [];

            // Iterate until you have reached the end date adding a week each time
            while ($date->lt($toDate)) {
                $dates[] = $date->toDateString();
                $date->addWeek();
            }
            
            $totalCount = $totalCount + count($dates);
            // Log::info($dates);
            // Log::info(count($dates));
        }

        foreach($holidays as $holiday){
            if($startDate >= $holiday->start_date && $endDate <= $holiday->end_date){
                return response() -> json([
                    'status'=>200,
                    'message' => 'Between holiday.',
                    'data' => ''
                ]);
            }elseif($startDate >= $holiday->start_date && $endDate > $holiday->end_date && $startDate > $holiday->end_date){
                $count = $this->countDays($startDate, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'No holiday!!!After',
                    'data' => ($count - $totalCount) + 1
                ]);
            }
            elseif($startDate < $holiday->start_date &&  $endDate <= $holiday->end_date && $endDate < $holiday->start_date){
                $count = $this->countDays($startDate, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'No holiday!!!Before',
                    'data' => ($count - $totalCount) + 1
                ]);
            }
            elseif($startDate >= $holiday->start_date && $endDate > $holiday->end_date){
                $count = $this->countDays($holiday->end_date, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'After the holiday end date.',
                    'data' => $count
                ]);

            }elseif($startDate < $holiday->start_date &&  $endDate <= $holiday->end_date){
                $count = $this->countDays($startDate, $holiday->start_date);
                return response() -> json([
                    'status'=>200,
                    'message' => 'Before the holiday start date.',
                    'data' => $count
                ]);
            }elseif($startDate < $holiday->start_date && $endDate > $holiday->end_date){
                // $countStart = $this->countDays($holiday->start_date, $startDate);
                // $countEnd = $this->countDays($endDate, $holiday->end_date);
                // $totalCount = $countStart + $countEnd;
                $count = $this->countDays($startDate, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'Before the holiday start date and after the holiday end date.',
                    'data' => ($count - $totalCount) + 1
                ]);
            }else{
                $count = $this->countDays($startDate, $endDate);
                return response() -> json([
                    'status'=>200,
                    'message' => 'No holiday.',
                    'data' => $count
                ]);
            }
        }

    }

    public function destroy($id){
        EmployeeLeave::find($id)->delete($id);

        return redirect('leave-apply-list')->with('status', 'Deleted successfully!');
    }

}
