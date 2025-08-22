<?php

namespace App\Http\Controllers;

use Log;

use Carbon\Carbon;
// use DB;
use App\Models\Attendance;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveReportController extends Controller
{
    public function index()
    {
        $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('report/leave-report', ['employees' => $employees]);
    }

    public function onLoad(Request $request)
    {

        $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();
        $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();

        // $totalPresentCount = 0;
        foreach ($employees as $employee) {
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

            $object = [
                'employeeId' => $employee->id,
                'employeeName' => $employee->employee_name,
                'employeeDepartment' => $employee->department,
                'employeeDesignation' => $employee->designation,
                'totalPresent' => $presentCount,
                'totalAbsent' => $absentCount,
                'totalLateIn' => $lateInCount,
                'totalEarlyOut' => $earlyOutCount,
            ];

            // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

            $data[] = $object;
        }

        // Log::info(json_encode($data));

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $data,
            'monthName' => strtoupper(Carbon::now()->format('F'))
        ]);
    }

    public function filter(Request $request)
    {

        $employees = DB::table('employee_tables')->where('subscriber_id', Auth::user()->subscriber_id)->get();
        $attendances = DB::table('attendances')->where('subscriber_id', Auth::user()->subscriber_id)->get();
        $startDate = $request->startdate;
        $endDate = $request->enddate;
        $department = $request->department;
        $employeeX = $request->employee;

        if ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('employee') == '') && ($request->input('department') == '')) {
            foreach ($employees as $employee) {
                $presentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'present'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $startDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $absentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'absent'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $startDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $lateInCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Late In'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $startDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $earlyOutCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Early out'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $startDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $object = [
                    'employeeId' => $employee->id,
                    'employeeName' => $employee->employee_name,
                    'employeeDepartment' => $employee->department,
                    'employeeDesignation' => $employee->designation,
                    'totalPresent' => $presentCount,
                    'totalAbsent' => $absentCount,
                    'totalLateIn' => $lateInCount,
                    'totalEarlyOut' => $earlyOutCount,
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            return response()->json([
                'status' => 200,
                'message' => 'Start Date',
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->filled('enddate') && ($request->input('employee') == '') && ($request->input('department') == '')) {

            foreach ($employees as $employee) {
                $presentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'present'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $endDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $absentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'absent'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $endDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $lateInCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Late In'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $endDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $earlyOutCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Early out'],
                        ['employee_id', $employee->id],
                        ['attendance_date', $endDate],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->count();

                $object = [
                    'employeeId' => $employee->id,
                    'employeeName' => $employee->employee_name,
                    'employeeDepartment' => $employee->department,
                    'employeeDesignation' => $employee->designation,
                    'totalPresent' => $presentCount,
                    'totalAbsent' => $absentCount,
                    'totalLateIn' => $lateInCount,
                    'totalEarlyOut' => $earlyOutCount,
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            return response()->json([
                'status' => 200,
                'message' => 'End Date',
                'data' => $data
            ]);
        } elseif ($request->input('startdate') && $request->input('enddate') && ($request->input('employee') == '') && ($request->input('department') == '')) {

            foreach ($employees as $employee) {
                $presentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'present'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    // ->whereBetween('attendance_date', [$startDate, $endDate])
                    ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->count();

                $absentCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'absent'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->count();

                $lateInCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Late In'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->count();

                $earlyOutCount = DB::table('attendances')
                    ->where([
                        ['attendance_status', 'Early out'],
                        ['employee_id', $employee->id],
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])
                    ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->count();

                $object = [
                    'employeeId' => $employee->id,
                    'employeeName' => $employee->employee_name,
                    'employeeDepartment' => $employee->department,
                    'employeeDesignation' => $employee->designation,
                    'totalPresent' => $presentCount,
                    'totalAbsent' => $absentCount,
                    'totalLateIn' => $lateInCount,
                    'totalEarlyOut' => $earlyOutCount,
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            return response()->json([
                'status' => 200,
                'message' => 'Start & End Date',
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->input('enddate') == NULL && ($request->input('employee') != '') && ($request->input('department') == '')) {

            $employee = DB::table('employee_tables')->where('id', $request->employee)->first();
            $presentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'present'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();

            $absentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'absent'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();

            $lateInCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Late In'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();

            $earlyOutCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Early out'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereMonth('attendance_date', date('m'))
                ->count();

            $object = [
                'employeeId' => $employee->id,
                'employeeName' => $employee->employee_name,
                'employeeDepartment' => $employee->department,
                'employeeDesignation' => $employee->designation,
                'totalPresent' => $presentCount,
                'totalAbsent' => $absentCount,
                'totalLateIn' => $lateInCount,
                'totalEarlyOut' => $earlyOutCount,
            ];

            $data[] = $object;

            return response()->json([
                'status' => 200,
                'message' => 'Employee',
                'data' => $data
            ]);
        } elseif ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('employee') != '') && ($request->input('department') == '')) {

            $employee = DB::table('employee_tables')->where('id', $request->employee)->first();
            $presentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'present'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $startDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $absentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'absent'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $startDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $lateInCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Late In'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $startDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $earlyOutCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Early out'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $startDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $object = [
                'employeeId' => $employee->id,
                'employeeName' => $employee->employee_name,
                'employeeDepartment' => $employee->department,
                'employeeDesignation' => $employee->designation,
                'totalPresent' => $presentCount,
                'totalAbsent' => $absentCount,
                'totalLateIn' => $lateInCount,
                'totalEarlyOut' => $earlyOutCount,
            ];

            $data[] = $object;

            return response()->json([
                'status' => 200,
                'message' => 'Employee & Start Date',
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->filled('enddate') && ($request->input('employee') != '') && ($request->input('department') == '')) {

            $employee = DB::table('employee_tables')->where('id', $request->employee)->first();
            $presentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'present'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $endDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $absentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'absent'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $endDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $lateInCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Late In'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $endDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $earlyOutCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Early out'],
                    ['employee_id', $request->employee],
                    ['attendance_date', $endDate],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->count();

            $object = [
                'employeeId' => $employee->id,
                'employeeName' => $employee->employee_name,
                'employeeDepartment' => $employee->department,
                'employeeDesignation' => $employee->designation,
                'totalPresent' => $presentCount,
                'totalAbsent' => $absentCount,
                'totalLateIn' => $lateInCount,
                'totalEarlyOut' => $earlyOutCount,
            ];

            $data[] = $object;

            return response()->json([
                'status' => 200,
                'message' => 'Employee & End Date',
                'data' => $data
            ]);
        } elseif ($request->filled('startdate') && $request->filled('enddate') && ($request->input('employee') != '') && ($request->input('department') == '')) {

            $employee = DB::table('employee_tables')->where('id', $request->employee)->first();
            $presentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'present'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();

            $absentCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'absent'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();

            $lateInCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Late In'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();

            $earlyOutCount = DB::table('attendances')
                ->where([
                    ['attendance_status', 'Early out'],
                    ['employee_id', $request->employee],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();

            $object = [
                'employeeId' => $employee->id,
                'employeeName' => $employee->employee_name,
                'employeeDepartment' => $employee->department,
                'employeeDesignation' => $employee->designation,
                'totalPresent' => $presentCount,
                'totalAbsent' => $absentCount,
                'totalLateIn' => $lateInCount,
                'totalEarlyOut' => $earlyOutCount,
            ];

            $data[] = $object;

            return response()->json([
                'status' => 200,
                'message' => 'Employee & Start & End Date',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'None'
            ]);
        }
    }

    public function details(Request $request, $id)
    {

        $employee = DB::table('employee_tables')->where('id', $id)->first();

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $data = Attendance::where([
            ['employee_id', $id]
        ])
            ->whereMonth('attendance_date', date('m'))
            ->get();

        return view('report/leave-report-details', ['employee' => $employee, 'data' => $data, 'date' => strtoupper(Carbon::now()->format('F'))]);
        // return response() -> json([
        //         'status'=>200,
        //         'message' => 'Success',
        //         'data' => $data
        //     ]);
    }

    public function detailsStartDate(Request $request, $id, $startDate)
    {
        $employee = DB::table('employee_tables')->where('id', $id)->first();
        $data = Attendance::where([
            ['employee_id', $id],
            ['attendance_date', $startDate]
        ])
            ->get();

        return view('report/leave-report-details', ['employee' => $employee, 'data' => $data, 'date' => $startDate]);
    }

    public function detailsEndtDate(Request $request, $id, $endDate)
    {
        $employee = DB::table('employee_tables')->where('id', $id)->first();
        $data = Attendance::where([
            ['employee_id', $id],
            ['attendance_date', $endDate]
        ])
            ->get();

        return view('report/leave-report-details', ['employee' => $employee, 'data' => $data, 'date' => $endDate]);
    }

    public function detailsStartEndDate(Request $request, $id, $startDate, $endDate)
    {
        $employee = DB::table('employee_tables')->where('id', $id)->first();
        $data = Attendance::where([
            ['employee_id', $id],
        ])
            ->whereBetween('attendance_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        return view('report/leave-report-details', ['employee' => $employee, 'data' => $data, 'date' => $startDate . ' to ' . $endDate]);
    }
}
