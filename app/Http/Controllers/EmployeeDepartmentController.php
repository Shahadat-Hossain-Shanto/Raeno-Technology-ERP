<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeDepartment;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeDepartmentController extends Controller
{
    public function create()
    {
        return view('employee-department/employee-department-add');
    }

    public function store(Request $request)
    {

        $messages = [
            'departmentname.required'  =>    "Department name is required.",
            // 'expirydate.required'  =>    "Expiry date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'departmentname' => 'required',
            // 'expirydate' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $employeeDepartment = new EmployeeDepartment;

            $employeeDepartment->department_name = $request->departmentname;
            $employeeDepartment->job_description = $request->jobdescription;
            $employeeDepartment->subscriber_id = Auth::user()->subscriber_id;
            // $employeeDepartment->user_id = Auth::user()->id;

            $employeeDepartment->save();

            return response()->json([
                'status' => 200,
                'message' => 'Employee Department created Successfully!'
            ]);
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function listView()
    {
        return view('employee-department/employee-department-list');
    }

    public function list(Request $request)
    {

        $employeeDepartment = EmployeeDepartment::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if ($request->ajax()) {
            return response()->json([
                'employeeDepartment' => $employeeDepartment,
                'message' => 'Success'
            ]);
        }
    }

    public function edit($id)
    {
        $employeeDepartment = EmployeeDepartment::find($id);

        if ($employeeDepartment) {
            return response()->json([
                'status' => 200,
                'employeeDepartment' => $employeeDepartment,

            ]);
        }
    }

    public function update(Request $request, $id)
    {

        $messages = [
            'departmentname.required'  =>    "Department name is required.",
            // 'expirydate.required'  =>    "Expiry date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'departmentname' => 'required',
            // 'expirydate' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $employeeDepartment = EmployeeDepartment::find($id);

            $employeeDepartment->department_name = $request->departmentname;
            $employeeDepartment->job_description = $request->jobdescription;

            $employeeDepartment->save();

            return response()->json([
                'status' => 200,
                'message' => 'Employee Department updated successfully'
            ]);
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        EmployeeDepartment::find($id)->delete($id);
        return redirect('employee-department-list')->with('status', 'Deleted successfully!');
    }
}
