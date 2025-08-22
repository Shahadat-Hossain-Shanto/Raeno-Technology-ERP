<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Salary;

class SalaryAPIController extends Controller
{
    public function store(Request $request){
        
        $salary = new Salary;

        $salary->employee_id = (int)$request->employeeId;
        $salary->employee_name = $request->employeeName;

        $salaryMonth             = strtotime($request->salaryMonth);
        $salary->salary_month   = date('Y-m-d', $salaryMonth);


        // $salary->salary_month = $request->salaryMonth;

        

        $salary->amount = doubleval($request->amount);
        $salary->note = $request->note;
        $salary->image = $request->image;
        $salary->subscriber_id = (int)$request->subscriberId;
        $salary->store_id = (int)$request->storeId;

        $salary->save();

        return response() -> json([
                'status'=>200,
                'message' => 'Salary created Successfully!'
            ]);
    }
}
