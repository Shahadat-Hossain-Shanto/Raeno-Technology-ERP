<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use DateTime;

use App\Models\EmployeeDepartment;
use App\Models\Attendance;
use App\Models\SalaryEmployee;

class SalaryCreateController extends Controller
{
    public function create(){

        $departments = EmployeeDepartment::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('salary/salary-create', ['departments' => $departments]);
    }

    public function onLoad(Request $request){

        // $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();
        
        $employees = DB::table('salary_grades')
        ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
        ->where([
            ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
            ['salary_grades.salary_type', 'monthly'],
        ])->get();

        // Log::info($employees);

        if($employees->isEmpty()){
            return response() -> json([
                'status'=>200,
                'message' => 'No Employee Found',
                'monthName' => Carbon::now()->format('F')  
            ]);
        }

        $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();

        foreach($employees as $employee){
            $presentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'present'],
                    ['employee_id', $employee->id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();

            $absentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'absent'],
                    ['employee_id', $employee->id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();
            
            $lateInCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Late In'],
                    ['employee_id', $employee->id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();
            
            $earlyOutCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Early out'],
                    ['employee_id', $employee->id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();
            
            $totalLeave = DB::table('employee_leaves')
                ->where([
                    ['leave_status', 'approve'],
                    ['employee_id', $employee->id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('end_date', date('m'))
                ->get();

            
            if($totalLeave->isEmpty()){
                $totalLeaves = 0;
            }else{
                foreach($totalLeave as $item){
                    $totalLeaves = $item->total_days;
                }
            }

            $grossSalary = DB::table('salary_grades')
            ->where([
                ['id', $employee->salary_grade_id],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])
            ->first();

            
            
            // Log::info($isSalaryPaid);
            $dateObj   = DateTime::createFromFormat('!m',  date('m'));
            $monthName = $dateObj->format('F'); // March    
            
            $object = [
                'employeeId'=>$employee->id,
                'employeeName'=>$employee->employee_name,
                'employeeDepartment'=>$employee->department,
                'employeeDesignation'=>$employee->designation,
                'basic_pay'=>$employee->basic_pay,
                'addition'=>$employee->addition,
                'deduction'=>$employee->deduction,
                //'absent_deduction'=>$absent_deduction,
                'totalPresent'=>$presentCount,
                'totalAbsent'=>$absentCount,
                'totalLateIn'=>$lateInCount,
                'totalEarlyOut'=>$earlyOutCount,
                'totalLeave'=>$totalLeaves,
                'grossSalary'=>$grossSalary->gross_salary,
                'grossSalaryId'=>$grossSalary->id,
                'isSalaryPaid'=>$this->checkIfSalaryPaid($employee->id, $monthName)
            ]; 
            
            // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);
            
            $data[] = $object;
            
            
        }

        if($data){
            return response() -> json([
                'status'=>200,
                'message' => 'Success',
                'data' => $data,
                'monthName' => Carbon::now()->format('F')  
            ]);
        }

        // Log::info(json_encode($data));

        
    }

    public function filter(Request $request){

        if($request->department  != '' && $request->month  != ''){

            $dateObj   = DateTime::createFromFormat('!m', $request->month);
            $monthName = $dateObj->format('F'); // March    

            $employees = DB::table('salary_grades')
            ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
            ->where([
                ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                ['salary_grades.salary_type', 'monthly'],
                ['employee_tables.department_id', $request->department],
            ])->get();

            // Log::info(date('m'));

            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();

            if($employees->isEmpty()){
                return response() -> json([
                    'status'=>200,
                    'message' => 'Success',
                    'data' => null,
                    'monthName' => $monthName  
                ]);
            }else{
                foreach($employees as $employee){
                    $presentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'present'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();

                    $absentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'absent'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();
                    
                    $lateInCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Late In'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();
                    
                    $earlyOutCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Early out'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();
                    
                    $totalLeave = DB::table('employee_leaves')
                        ->where([
                            ['leave_status', 'approve'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('end_date', $request->month)
                        ->get();

                    if($totalLeave->isEmpty()){
                        $totalLeaves = 0;
                    }else{
                        foreach($totalLeave as $item){
                            $totalLeaves = $item->total_days;
                        }
                    }

                    $grossSalary = DB::table('salary_grades')
                    ->where([
                        ['id', $employee->salary_grade_id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->first();
                    
                    
                    $object = [
                        'employeeId'=>$employee->id,
                        'employeeName'=>$employee->employee_name,
                        'employeeDepartment'=>$employee->department,
                        'employeeDesignation'=>$employee->designation,
                        'basic_pay'=>$employee->basic_pay,
                        'addition'=>$employee->addition,
                        'deduction'=>$employee->deduction,
                        //'absent_deduction'=>$absent_deduction,
                        'totalPresent'=>$presentCount,
                        'totalAbsent'=>$absentCount,
                        'totalLateIn'=>$lateInCount,
                        'totalEarlyOut'=>$earlyOutCount,
                        'totalLeave'=>$totalLeaves,
                        'grossSalary'=>$grossSalary->gross_salary,
                        'grossSalaryId'=>$grossSalary->id,
                        'isSalaryPaid'=>$this->checkIfSalaryPaid($employee->id, $monthName)
                    ]; 
                    
                    // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);
                    
                    $data[] = $object;
                    
                    
                }

                return response() -> json([
                    'status'=>200,
                    'message' => 'Success',
                    'data' => $data,
                    'monthName' => $monthName
                ]);
            }
        }elseif($request->department  == '' && $request->month  != ''){

            $dateObj   = DateTime::createFromFormat('!m', $request->month);
            $monthName = $dateObj->format('F'); // March    
            $employees = DB::table('salary_grades')
            ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
            ->where([
                ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                ['salary_grades.salary_type', 'monthly'],
               
            ])->get();

            // Log::info(date('m'));

            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();

            if($employees->isEmpty()){
                return response() -> json([
                    'status'=>200,
                    'message' => 'Success',
                    'data' => null,
                    'monthName' => $monthName
                ]);
            }else{
                foreach($employees as $employee){
                    $presentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'present'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();

                    $absentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'absent'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();
                    
                    $lateInCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Late In'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();
                    
                    $earlyOutCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Early out'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('attendance_date', $request->month)
                        ->count();
                    
                    $totalLeave = DB::table('employee_leaves')
                        ->where([
                            ['leave_status', 'approve'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('end_date', $request->month)
                        ->get();

                    if($totalLeave->isEmpty()){
                        $totalLeaves = 0;
                    }else{
                        foreach($totalLeave as $item){
                            $totalLeaves = $item->total_days;
                        }
                    }

                    $grossSalary = DB::table('salary_grades')
                    ->where([
                        ['id', $employee->salary_grade_id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->first();
                    
                    
                    $object = [
                        'employeeId'=>$employee->id,
                        'employeeName'=>$employee->employee_name,
                        'employeeDepartment'=>$employee->department,
                        'employeeDesignation'=>$employee->designation,
                        'basic_pay'=>$employee->basic_pay,
                        'addition'=>$employee->addition,
                        'deduction'=>$employee->deduction,
                        //'absent_deduction'=>$absent_deduction,
                        'totalPresent'=>$presentCount,
                        'totalAbsent'=>$absentCount,
                        'totalLateIn'=>$lateInCount,
                        'totalEarlyOut'=>$earlyOutCount,
                        'totalLeave'=>$totalLeaves,
                        'grossSalary'=>$grossSalary->gross_salary,
                        'grossSalaryId'=>$grossSalary->id,
                        'isSalaryPaid'=>$this->checkIfSalaryPaid($employee->id, $monthName)
                    ]; 
                    // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);
                    
                    $data[] = $object;
                    
                    
                }

                return response() -> json([
                    'status'=>200,
                    'message' => 'Success',
                    'data' => $data,
                    'monthName' => $monthName
                ]);
            }
        }elseif($request->department  != '' && $request->month  == ''){
            log::info($request->department);
            $employees = DB::table('salary_grades')
            ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
            ->where([
                ['employee_tables.department_id', $request->department],
                ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                ['salary_grades.salary_type', 'monthly'],
               // 
            ])->get();
                log::info($employees);
            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();
            // $leaves = DB::table('leaves')->where('subscriber_id', Auth::user()->subscriber_id)->get();

            // $totalPresentCount = 0;

            if($employees->isEmpty()){
                return response() -> json([
                    'status'=>200,
                    'message' => 'No Employee Found',
                    'monthName' => Carbon::now()->format('F')  
                ]);
            }
            
            foreach($employees as $employee){
                $presentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'present'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereMonth('attendance_date', date('m'))
                    ->count();

                $absentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'absent'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereMonth('attendance_date', date('m'))
                    ->count();
                
                $lateInCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Late In'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereMonth('attendance_date', date('m'))
                    ->count();
                
                $earlyOutCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Early out'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereMonth('attendance_date', date('m'))
                    ->count();
                
                $totalLeave = DB::table('employee_leaves')
                    ->where([
                        ['leave_status', 'approve'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereMonth('end_date', date('m'))
                    ->get();

                
                if($totalLeave->isEmpty()){
                    $totalLeaves = 0;
                }else{
                    foreach($totalLeave as $item){
                        $totalLeaves = $item->total_days;
                    }
                }

                $grossSalary = DB::table('salary_grades')
                ->where([
                    ['id', $employee->salary_grade_id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->first();
                
                $dateObj   = DateTime::createFromFormat('!m',  date('m'));
                $monthName = $dateObj->format('F'); // March    
                
                $object = [
                    'employeeId'=>$employee->id,
                    'employeeName'=>$employee->employee_name,
                    'employeeDepartment'=>$employee->department,
                    'employeeDesignation'=>$employee->designation,
                    'basic_pay'=>$employee->basic_pay,
                    'addition'=>$employee->addition,
                    'deduction'=>$employee->deduction,
                    //'absent_deduction'=>$absent_deduction,
                    'totalPresent'=>$presentCount,
                    'totalAbsent'=>$absentCount,
                    'totalLateIn'=>$lateInCount,
                    'totalEarlyOut'=>$earlyOutCount,
                    'totalLeave'=>$totalLeaves,
                    'grossSalary'=>$grossSalary->gross_salary,
                    'grossSalaryId'=>$grossSalary->id,
                    'isSalaryPaid'=>$this->checkIfSalaryPaid($employee->id, $monthName)
                ]; 
                
                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);
                
                $data[] = $object;
                
                
            }

            // Log::info(json_encode($data));

            return response() -> json([
                'status'=>200,
                'message' => 'Success',
                'data' => $data,
                'monthName' => Carbon::now()->format('F')  
            ]);
        }else{
            return response() -> json([
                'status'=>200,
                'message' => 'Please select department',
            ]);
        }
        
    }

    public static function checkIfSalaryPaid($empId, $month){
        $checkIfSalaryPaid = SalaryEmployee::where([
            ['employee_id', $empId],
            ['salary_month', $month],
            ['subscriber_id', Auth::user()->subscriber_id]
        ])->get();

        if($checkIfSalaryPaid->isEmpty()){
            $isSalaryPaid = 'false';
        }else{
            $isSalaryPaid = 'true';
        }

        return $isSalaryPaid;
    }

}
