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
use App\Models\PayBenefit;

class PayBenefitSlipController extends Controller
{
    public function show($employeeId, $id){

        $date = Carbon::now();

        $data1 = PayBenefit::where([

            ['employee_id', $employeeId],
            ['id', $id],
            ['year', $date->year]
        ])->first();

        $data2 = PayBenefit::where([

            ['employee_id', $employeeId],
            ['id', $id],
            ['year', $date->year]
        ])->get();

        // Log::info($data);

        $total = 0;
        foreach($data2 as $item){
            $total = $total + $item->amount;
        }

        return view('pay-benefit/pay-benefit-slip', ['data1' => $data1, 'data2' => $data2, 'total' => $total]);
    }
}
