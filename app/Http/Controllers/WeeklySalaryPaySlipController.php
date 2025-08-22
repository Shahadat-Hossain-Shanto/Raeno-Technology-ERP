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
use App\Models\User;
use App\Models\WeeklySalaryEmployee;
use App\Models\BenefitList;

class WeeklySalaryPaySlipController extends Controller
{
    public function show($employeeId, $weekStartDate, $weekEndDate){



        $salaries = WeeklySalaryEmployee::where([
            ['employee_id', $employeeId],
        ])
        ->where('salary_date', '>=', $weekStartDate)
        ->where('salary_date', '<=', $weekEndDate)
        ->first();
            $id=$salaries->salary_grade_id;

        $benefits_add = DB::table('benefit_lists')
        ->select('benefit_lists.benefit_id', 'benefit_lists.benefit_type', 'benefit_lists.benefit_name as name', 'benefit_lists.amount as amount', 'benefits.payment_type')
        ->join('benefits', 'benefit_lists.benefit_id', '=', 'benefits.id')
        ->where([['benefit_lists.subscriber_id', '=', Auth::user()->subscriber_id], ['benefit_lists.grade_id', '=', $id], ['benefit_lists.benefit_type', '=', 'add'],['benefits.benefit_regularity', '=', 'regular']])
        ->get();
        $benefits_deduct = DB::table('benefit_lists')
        ->select('benefit_lists.benefit_id', 'benefit_lists.benefit_type', 'benefit_lists.benefit_name as name', 'benefit_lists.amount as amount', 'benefits.payment_type')
        ->join('benefits', 'benefit_lists.benefit_id', '=', 'benefits.id')
        ->where([['benefit_lists.subscriber_id', '=', Auth::user()->subscriber_id], ['benefit_lists.grade_id', '=', $id], ['benefit_lists.benefit_type', '=', 'deduct'],['benefits.benefit_regularity', '=', 'regular']])
        ->get();
        //log::info(Auth::user()->name);
        //log::info($benefits_deduct);
        $payWeek = $weekStartDate.' TO '.$weekEndDate;

        $employee_name =$salaries->employee_name;
       $sub_name= Auth::user()->name;
       $sub_email=Auth::user()->email;
       $sub_no=Auth::user()->contact_number;
        $additions =$salaries->addition;
        $net_pay=$salaries->net_pay;
        //log::info($net_pay);
        $deductions = $salaries->deduction;
        $absent = $salaries->abesnt;
        $absentDeduction = $salaries->absent_deduction;
        $basic_pay = $salaries->basic_pay;
        $department= $salaries->department;
        $designation  =$salaries->designation;
        $total_earning=$basic_pay+$additions;
        $total_deductions=$deductions+$absentDeduction;

        return view('weekly-salary/weekly-salary-pay-slip', ['employee_name' => $employee_name, 'addition' => $additions,  'net_payment'=>$net_pay,
        'absent'=>$absent, 'absentDeduction'=>$absentDeduction, 'basic_pay'=>$basic_pay, 'department'=>$department ,
         'designation'=>$designation, 'payWeek'=>$payWeek, 'benefits_add'=>$benefits_add, 'benefits_deduct'=>$benefits_deduct,
          'total_earning'=>$total_earning,'sub_name'=>$sub_name,'sub_email'=>$sub_email,'sub_no'=>$sub_no, 'total_deductions'=>$total_deductions]);
    }
}
