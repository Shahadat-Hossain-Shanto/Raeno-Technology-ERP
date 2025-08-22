<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\EmployeeTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDepartment;
use App\Models\SalaryGrade;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function create()
    {
        $designations = Designation::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $employeeDepartments = EmployeeDepartment::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $salaryGrades = SalaryGrade::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('employee.employee-add', compact('designations', 'employeeDepartments', 'salaryGrades'));
    }

    public function index()
    {
        $employees = EmployeeTable::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('employee.employee-list', compact('employees'));
    }

    public function indexData(Request $req)
    {
        $employee = EmployeeTable::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // Log::info($employee);
        if ($req->ajax()) {
            return response()->json([
                'employee' => $employee,
                'messeage' => 'Success123123123123'
            ]);
        }
    }

    public function store(Request $req)
    {
        $messages = [
            'employee_name.required'  =>    "Employee Name is required.",
            'employee_name.max'       =>    "Max 255 characters.",
            'email.required'          =>    "Employee email is required.",
            'email.required'          =>    "Email is required.",
            'email.email'             =>    "Email is not valid.",
            'email.max'               =>    "Max 255 characters.",
            'email.unique'            =>    "Email already exists.",
            'phone.unique'            =>    "Phone Number already exists.",
            'phone.required'          =>    "Employee phone is required.",
            'designation.required'    =>    "Employee designation is required.",
            'employee_type.required'  =>    "Employee type is required.",
            'blood_group.required'    =>    "Blood group is required.",
            'address.required'        =>    "Address is required.",
            'city.required'           =>    "City is required.",
            'image.required'          =>    "Image is required.",
            'status.required'         =>    "Status is required.",
            'employeedepartment.required'  =>    "Employee Department is required.",
            // 'salarygrade.required'  =>    "Salary grade is required.",

        ];

        $validator = Validator::make($req->all(), [
            'employee_name'  => ['required', 'max:255'],
            'employeedepartment'  => ['required', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:employee_tables'],
            'phone'          => ['required', 'unique:employee_tables'],
            'designation'    => ['required'],
            'employee_type'  => ['required'],
            'blood_group'    => ['required'],
            'address'        => ['required'],
            'city'           => ['required'],
            'image'          => ['required'],
            'status'         => ['required'],
            'employeedepartment'         => ['required'],
            // 'salarygrade'         => ['required'],
        ], $messages);

        if ($validator->passes()) {
            $employee = new EmployeeTable();
            $employee->employee_name =  $req->employee_name;
            $employee->email         =  $req->email;
            $employee->phone         =  $req->phone;
            $employee->designation   =  $req->designation;
            $employee->employee_type =  $req->employee_type;
            $employee->blood_group   =  $req->blood_group;
            $employee->address       =  $req->address;
            $employee->city          =  $req->city;

            //default , change later
            $employee->subscriber_id = Auth::user()->subscriber_id;
            $employee->salary_type   = $req->salarytype;
            $employee->department    = $req->employeedepartment_name;
            $employee->department_id = $req->employeedepartment;
            $employee->salary_grade = $req->salary_grade;
            $employee->salary_grade_id = $req->salarygrade;
            $employee->hourly_payment = $req->hourlypaymentamount;

            if ($req->hasFile('image')) {

                // $path = 'uploads/clients/'.$customer->image;
                // if(File::exists($path)){
                //     File::delete($path);
                // }

                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = $file->getClientOriginalName();
                $file->move('uploads/employee/', $filename);
                // $supplierImage->imageName  = $filename;
                $employee->image            = $filename;
            }
            // $employee->image         =  $req->image;
            $employee->status        =  $req->status;

            $employee->save();
            return redirect()->route('designation.list.view');
        }
        // return json_encode(['error' => $validator->errors()]);
        return response()->json(['error' => $validator->errors()]);
    }


    public function edit(Request $req, $id)
    {
        $employee = EmployeeTable::find($id);
        $employeeDepartments = EmployeeDepartment::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $designations = Designation::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $salaryGrades = SalaryGrade::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if ($req->ajax()) {
            return response()->json([
                'status' => 200,
                'employee' => $employee,
            ]);
        }
        return view('employee.employee-edit', compact('employee', 'employeeDepartments', 'designations', 'salaryGrades'));
    }

    public function update(Request $req, $id)
    {
        $messages = [
            'employee_name.required'  =>    "Employee Name is required.",
            'employee_name.max'       =>    "Max 255 characters.",
            'email.required'          =>    "Employee email is required.",

            'email.email'             =>    "Email is not valid.",
            'email.max'               =>    "Max 255 characters.",
            'phone.required'          =>    "Employee phone is required.",
            'designation.required'    =>    "Employee designation is required.",
            'employee_type.required'  =>    "Employee type is required.",
            'blood_group.required'    =>    "Blood group is required.",
            'address.required'        =>    "Address is required.",
            'city.required'           =>    "City is required.",
            'status.required'         =>    "Status is required.",
            // 'salarygrade.required'  =>    "Salary grade is required.",

        ];
        $validator = Validator::make($req->all(), [
            'employee_name'  => ['required', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['required'],
            'designation'    => ['required'],
            'employee_type'  => ['required'],
            'blood_group'    => ['required'],
            'address'        => ['required'],
            'city'           => ['required'],
            'status'         => ['required'],
            // 'salarygrade'         => ['required'],
        ], $messages);


        if ($validator->passes()) {
            $employee = EmployeeTable::find($id);
            $employee->employee_name =  $req->employee_name;
            $employee->email         =  $req->email;
            $employee->phone         =  $req->phone;
            $employee->designation   =  $req->designation;
            $employee->employee_type =  $req->employee_type;
            $employee->blood_group   =  $req->blood_group;
            $employee->address       =  $req->address;
            $employee->city          =  $req->city;

            //default , change later
            $employee->subscriber_id = Auth::user()->subscriber_id;
            $employee->salary_type   = $req->salarytype;
            $employee->department    = $req->employeedepartment_name;
            $employee->department_id    = $req->employeedepartment;
            $employee->salary_grade = $req->salary_grade;
            $employee->salary_grade_id = $req->salarygrade;
            $employee->hourly_payment = $req->hourlypaymentamount;


            if ($req->hasFile('image')) {

                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                $filename = $file->getClientOriginalName();
                $file->move('uploads/employee/', $filename);
                // $supplierImage->imageName  = $filename;
                $employee->image            = $filename;
            }
            // $employee->image         =  $req->image;
            $employee->status        =  $req->status;
            $employee->save();
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        EmployeeTable::find($id)->delete($id);

        return redirect()->route('employee.list.view')->with('status', 'Deleted successfully!');
    }
}
