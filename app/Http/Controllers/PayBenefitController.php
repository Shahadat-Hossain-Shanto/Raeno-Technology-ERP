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
use App\Models\BenefitList;
use App\Models\Benefit;
use App\Models\EmployeeTable;
use App\Models\PayBenefit;

class PayBenefitController extends Controller
{
    public function create()
    {

        $specialBenefits = Benefit::join('benefit_lists', 'benefits.id', 'benefit_lists.benefit_id')
            ->where([
                ['benefits.benefit_regularity', 'special'],
                ['benefits.status', '1'],
                ['benefits.subscriber_id', Auth::user()->subscriber_id]
            ])->get();

        $departments = EmployeeDepartment::where('subscriber_id', Auth::user()->subscriber_id)->get();

        // Log::info($specialBenefits);

        return view('pay-benefit/pay-benefit-add', ['specialBenefits' => $specialBenefits, 'departments' => $departments]);
    }

    public function  onLoad()
    {

        $dataX = EmployeeTable::join('benefit_lists', 'employee_tables.salary_grade_id', 'benefit_lists.grade_id')
            ->join('benefits', 'benefit_lists.benefit_id', 'benefits.id')
            ->select(
                'employee_tables.id as employee_id',
                'employee_tables.employee_name',
                'employee_tables.designation',
                'employee_tables.department',
                'employee_tables.department_id',
                'benefit_lists.*',
                'benefits.*'
            )
            ->where([
                ['benefit_lists.subscriber_id', Auth::user()->subscriber_id],
                ['employee_tables.subscriber_id', Auth::user()->subscriber_id],
                ['benefits.benefit_regularity', 'special'],
                ['benefits.status', '1']
            ])
            ->get();

        if ($dataX->isEmpty()) {
            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Ok',
            ]);
        } else {
            foreach ($dataX as $item) {
                $object = [
                    'employee_id' => $item->employee_id,
                    'employee_name' => $item->employee_name,
                    'department' => $item->department,
                    'department_id' => $item->department_id,
                    'designation' => $item->designation,
                    'benefit_name' => $item->benefit_name,
                    'benefit_id' => $item->benefit_id,
                    'total_amount' => $item->total_amount,
                    'yearly_allocation' => $item->yearly_allocation,
                    'benefitPaid' => $this->checkCountOfPayment($item->employee_id, $item->benefit_id)
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Ok',
            ]);
        }
    }

    public function filter(Request $request)
    {

        if ($request->benefit  != '' && $request->department  != '') {

            $dataX = EmployeeTable::join('benefit_lists', 'employee_tables.salary_grade_id', 'benefit_lists.grade_id')
                ->join('benefits', 'benefit_lists.benefit_id', 'benefits.id')
                ->select(
                    'employee_tables.id as employee_id',
                    'employee_tables.employee_name',
                    'employee_tables.designation',
                    'employee_tables.department',
                    'employee_tables.department_id',
                    'benefit_lists.*',
                    'benefits.*'
                )
                ->where([
                    ['benefit_lists.subscriber_id', Auth::user()->subscriber_id],
                    ['benefits.benefit_regularity', 'special'],
                    ['benefits.status', '1'],
                    ['employee_tables.department_id', $request->department],
                    ['benefit_lists.id', $request->benefit]
                ])
                ->get();

            foreach ($dataX as $item) {
                $object = [
                    'employee_id' => $item->employee_id,
                    'employee_name' => $item->employee_name,
                    'department' => $item->department,
                    'department_id' => $item->department_id,
                    'designation' => $item->designation,
                    'benefit_name' => $item->benefit_name,
                    'benefit_id' => $item->benefit_id,
                    'total_amount' => $item->total_amount,
                    'yearly_allocation' => $item->yearly_allocation,
                    'benefitPaid' => $this->checkCountOfPayment($item->employee_id, $item->benefit_id)
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            if ($dataX->isEmpty()) {
                $data = null;
            }

            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Ok',
            ]);
        } elseif ($request->benefit  == '' && $request->department  != '') {
            $dataX = EmployeeTable::join('benefit_lists', 'employee_tables.salary_grade_id', 'benefit_lists.grade_id')
                ->join('benefits', 'benefit_lists.benefit_id', 'benefits.id')
                ->select(
                    'employee_tables.id as employee_id',
                    'employee_tables.employee_name',
                    'employee_tables.designation',
                    'employee_tables.department',
                    'employee_tables.department_id',
                    'benefit_lists.*',
                    'benefits.*'
                )
                ->where([
                    ['benefit_lists.subscriber_id', Auth::user()->subscriber_id],
                    ['benefits.benefit_regularity', 'special'],
                    ['employee_tables.department_id', $request->department]
                ])
                ->get();

            foreach ($dataX as $item) {
                $object = [
                    'employee_id' => $item->employee_id,
                    'employee_name' => $item->employee_name,
                    'department' => $item->department,
                    'department_id' => $item->department_id,
                    'designation' => $item->designation,
                    'benefit_name' => $item->benefit_name,
                    'benefit_id' => $item->benefit_id,
                    'total_amount' => $item->total_amount,
                    'yearly_allocation' => $item->yearly_allocation,
                    'benefitPaid' => $this->checkCountOfPayment($item->employee_id, $item->benefit_id)
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            if ($dataX->isEmpty()) {
                $data = null;
            }

            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Ok',
            ]);
        } elseif ($request->benefit  != '' && $request->department  == '') {
            $dataX = EmployeeTable::join('benefit_lists', 'employee_tables.salary_grade_id', 'benefit_lists.grade_id')
                ->join('benefits', 'benefit_lists.benefit_id', 'benefits.id')
                ->select(
                    'employee_tables.id as employee_id',
                    'employee_tables.employee_name',
                    'employee_tables.designation',
                    'employee_tables.department',
                    'employee_tables.department_id',
                    'benefit_lists.*',
                    'benefits.*'
                )
                ->where([
                    ['benefit_lists.subscriber_id', Auth::user()->subscriber_id],
                    ['benefits.benefit_regularity', 'special'],
                    ['benefits.status', '1'],
                    ['benefit_lists.id', $request->benefit]
                ])
                ->get();



            foreach ($dataX as $item) {
                $object = [
                    'employee_id' => $item->employee_id,
                    'employee_name' => $item->employee_name,
                    'department' => $item->department,
                    'department_id' => $item->department_id,
                    'designation' => $item->designation,
                    'benefit_name' => $item->benefit_name,
                    'benefit_id' => $item->benefit_id,
                    'total_amount' => $item->total_amount,
                    'yearly_allocation' => $item->yearly_allocation,
                    'benefitPaid' => $this->checkCountOfPayment($item->employee_id, $item->benefit_id)
                ];

                // Log::info($employee->employee_name.' Present '.$presentCount.' Absent '.$absentCount.' Late In '.$lateInCount.' Early out '.$earlyOutCount);

                $data[] = $object;
            }

            if ($dataX->isEmpty()) {
                $data = null;
            }

            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Ok',
            ]);
        }
    }

    public static function checkCountOfPayment($empId, $benefitId)
    {
        $date = Carbon::now();
        $checkCountPayment = PayBenefit::where([
            ['employee_id', $empId],
            ['benefit_id', $benefitId],
            ['year', $date->year],
            ['subscriber_id', Auth::user()->subscriber_id]
        ])->count();

        return $checkCountPayment;
    }

    public function history(Request $request, $empId)
    {
        $date = Carbon::now();
        $data = PayBenefit::where([
            ['employee_id', $empId],
            ['subscriber_id', Auth::user()->subscriber_id]
        ])->get();
        //log::info($data);

       if($request->ajax()){
        return response()->json([
            'status' => 200,
            'data' => $data,
            'message' => 'Ok',
        ]);
    }
    else{
        return view('pay-benefit/histories', ['empId' => $empId]);
    }


    }

    public function store(Request $request)
    {
        $date = Carbon::now();
        foreach ($request->benefitList as $benefit) {
            $payBenefit = new PayBenefit;
            $payBenefit->employee_id = $benefit['employee_id'];
            $payBenefit->employee_name = $benefit['employee_name'];
            $payBenefit->designation = $benefit['designation'];
            $payBenefit->department = $benefit['department'];
            $payBenefit->benefit_name = $benefit['benefit_name'];
            $payBenefit->benefit_id = $benefit['benefit_id'];
            $payBenefit->amount = $benefit['total_amount'];
            $payBenefit->year = $date->year;
            $payBenefit->month = Carbon::now()->format('F');
            $payBenefit->subscriber_id = Auth::user()->subscriber_id;
            $payBenefit->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Benefit paid successfully.',
        ]);
    }
}
