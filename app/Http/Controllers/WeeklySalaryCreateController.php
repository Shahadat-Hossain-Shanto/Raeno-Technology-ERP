<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\SalaryEmployee;
// use DB;
use App\Models\EmployeeDepartment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\WeeklySalaryEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;




class WeeklySalaryCreateController extends Controller
{
    public function create()
    {

        $departments = EmployeeDepartment::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('weekly-salary/weekly-salary-create', ['departments' => $departments]);
    }

    public function onLoad(Request $request)
    {

        // $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();

        $employees = DB::table('salary_grades')
            ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
            ->where([
                ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                ['salary_grades.salary_type', 'weekly'],
            ])->get();

        // Log::info($employees);
        if($employees->isEmpty()){

            $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

            $week = $weekStartDate . ' TO ' . $weekEndDate;
                
            return response()->json([
                'status' => 200,
                'message' => 'No weekly employee found.',
                'weekName' => $week,
                'weekStartDate' => $weekStartDate,
                'weekEndDate' => $weekEndDate,

            ]);
        }else{
            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();

            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            foreach ($employees as $employee) {
                $presentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'present'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    // ->whereMonth('attendance_date', date('m'))
                    ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                    ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                    ->count();

                $absentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'absent'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    // ->whereMonth('attendance_date', date('m'))
                    ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                    ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                    ->count();

                $lateInCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Late In'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    // ->whereMonth('attendance_date', date('m'))
                    ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                    ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                    ->count();

                $earlyOutCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Early out'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    // ->whereMonth('attendance_date', date('m'))
                    ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                    ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                    ->count();

                $totalLeave = DB::table('employee_leaves')
                    ->where([
                        ['leave_status', 'approve'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereMonth('end_date', date('m'))
                    ->get();


                if ($totalLeave->isEmpty()) {
                    $totalLeaves = 0;
                } else {
                    foreach ($totalLeave as $item) {
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

                $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

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
                'weekStartDate' => $weekStartDate,
                'weekEndDate' => $weekEndDate,
                'isSalaryPaid' => $this->checkIfSalaryPaid($employee->id, $weekStartDate, $weekEndDate)
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
                //log::info($data);
            }

            // Log::info(json_encode($data));
            // Log::info(Carbon::now()->startOfWeek().' to '.Carbon::now()->endOfWeek());
            $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

            $week = $weekStartDate . ' TO ' . $weekEndDate;

            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => $data,
                'weekName' => $week,
                'weekStartDate' => $weekStartDate,
                'weekEndDate' => $weekEndDate,

            ]);
        }

        
    }

    public function filter(Request $request)
    {

        $weekStartDate = $request->startDate;
        $weekEndDate = $request->endDate;

        $week = $weekStartDate . ' TO ' . $weekEndDate;

        if ($request->department  != '' && $request->startDate  != '' && $request->endDate  != '') {



            $employees = DB::table('salary_grades')
                ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
                ->where([
                    ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                    ['employee_tables.department_id', $request->department],
                    ['salary_grades.salary_type', 'weekly'],
                ])->get();

            // Log::info($employees);

            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();


            if ($employees->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => null,
                    'weekName' => $week,
                    'weekStartDate' => $weekStartDate,
                    'weekEndDate' => $weekEndDate,

                ]);
            } else {
                foreach ($employees as $employee) {
                    $presentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'present'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $absentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'absent'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $lateInCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Late In'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $earlyOutCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Early out'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $totalLeave = DB::table('employee_leaves')
                        ->where([
                            ['leave_status', 'approve'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('end_date', date('m'))
                        ->get();


                    if ($totalLeave->isEmpty()) {
                        $totalLeaves = 0;
                    } else {
                        foreach ($totalLeave as $item) {
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

                    $weekStartDate = $weekStartDate;
                    $weekEndDate = $weekEndDate;

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
                        'weekStartDate' => $weekStartDate,
                        'weekEndDate' => $weekEndDate,
                        'isSalaryPaid' => $this->checkIfSalaryPaid($employee->id, $weekStartDate, $weekEndDate)
                    ];

                    // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                    $data[] = $object;
                   // log::info($data);
                }

                $week = $weekStartDate . ' TO ' . $weekEndDate;

                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data,
                    'weekName' => $week,
                    'weekStartDate' => $weekStartDate,
                    'weekEndDate' => $weekEndDate,

                ]);
            }
        } else if ($request->department  == '' && $request->startDate  != '' && $request->endDate  != '') {
            $employees = DB::table('salary_grades')
                ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
                ->where([
                    ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                    ['salary_grades.salary_type', 'weekly'],
                ])->get();

            // Log::info($employees);

            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();


            if ($employees->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => null,
                    'weekName' => $week,
                    'weekStartDate' => $weekStartDate,
                    'weekEndDate' => $weekEndDate,

                ]);
            } else {
                foreach ($employees as $employee) {
                    $presentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'present'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $absentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'absent'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $lateInCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Late In'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $earlyOutCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Early out'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>=', $weekStartDate)
                        ->where('attendance_date', '<=', $weekEndDate)
                        ->count();

                    $totalLeave = DB::table('employee_leaves')
                        ->where([
                            ['leave_status', 'approve'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('end_date', date('m'))
                        ->get();


                    if ($totalLeave->isEmpty()) {
                        $totalLeaves = 0;
                    } else {
                        foreach ($totalLeave as $item) {
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

                    $weekStartDate = $weekStartDate;
                    $weekEndDate = $weekEndDate;

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
                        'weekStartDate' => $weekStartDate,
                        'weekEndDate' => $weekEndDate,
                        'isSalaryPaid' => $this->checkIfSalaryPaid($employee->id, $weekStartDate, $weekEndDate)
                    ];

                    // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                    $data[] = $object;
                   // log::info($data);
                }

                $week = $weekStartDate . ' TO ' . $weekEndDate;

                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data,
                    'weekName' => $week,
                    'weekStartDate' => $weekStartDate,
                    'weekEndDate' => $weekEndDate,

                ]);
            }
        } else if ($request->department  != '' && $request->startDate  == '' && $request->endDate  == '') {
            $employees = DB::table('salary_grades')
                ->join('employee_tables', 'employee_tables.salary_grade_id', 'salary_grades.id')
                ->where([
                    ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                    ['employee_tables.department_id', $request->department],
                    ['salary_grades.salary_type', 'weekly'],
                ])->get();

            // Log::info($employees);

            $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();


            if ($employees->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => null,
                    'weekName' => $week,
                    'weekStartDate' => $weekStartDate,
                    'weekEndDate' => $weekEndDate,

                ]);
            } else {
                Carbon::setWeekStartsAt(Carbon::SUNDAY);
                Carbon::setWeekEndsAt(Carbon::SATURDAY);

                foreach ($employees as $employee) {
                    $presentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'present'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                        ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                        ->count();

                    $absentCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'absent'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                        ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                        ->count();

                    $lateInCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Late In'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                        ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                        ->count();

                    $earlyOutCount = DB::table('attendances')
                        ->where([
                            ['attendance_status', 'Early out'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        // ->whereMonth('attendance_date', date('m'))
                        ->where('attendance_date', '>', Carbon::now()->startOfWeek())
                        ->where('attendance_date', '<', Carbon::now()->endOfWeek())
                        ->count();

                    $totalLeave = DB::table('employee_leaves')
                        ->where([
                            ['leave_status', 'approve'],
                            ['employee_id', $employee->id],
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])
                        ->whereMonth('end_date', date('m'))
                        ->get();


                    if ($totalLeave->isEmpty()) {
                        $totalLeaves = 0;
                    } else {
                        foreach ($totalLeave as $item) {
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

                    $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

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
                        'weekStartDate' => $weekStartDate,
                        'weekEndDate' => $weekEndDate,
                        'isSalaryPaid' => $this->checkIfSalaryPaid($employee->id, $weekStartDate, $weekEndDate)
                    ];

                    // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                    $data[] = $object;
                    //log::info($data);

                }

                // Log::info(json_encode($data));
                // Log::info(Carbon::now()->startOfWeek().' to '.Carbon::now()->endOfWeek());
                $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

                $week = $weekStartDate . ' TO ' . $weekEndDate;

                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data,
                    'weekName' => $week,
                    'weekStartDate' => $weekStartDate,
                    'weekEndDate' => $weekEndDate,

                ]);
            }
        }
    }

    public static function checkIfSalaryPaid($empId, $weekStartDate, $weekEndDate)
    {
        $checkIfSalaryPaid = WeeklySalaryEmployee::where([
            ['employee_id', $empId],
            ['subscriber_id', Auth::user()->subscriber_id]
        ])
        ->whereBetween('salary_date', [ $weekStartDate, $weekEndDate])
    ->whereBetween('salary_date_2', [ $weekStartDate, $weekEndDate])
            // ->Where([['salary_date', '<=',$weekStartDate ],
            // ['salary_date', '>=',$weekEndDate ]])
            // ->orWhere([['salary_date_2','>=', $weekStartDate],
            // ['salary_date_2','<=', $weekEndDate]])
            ->get();

        if ($checkIfSalaryPaid->isEmpty()) {
            $isSalaryPaid = 'false';
        } else {
            $isSalaryPaid = 'true';
        }

        // Log::info($weekStartDate);
        return $isSalaryPaid;
    }
}
