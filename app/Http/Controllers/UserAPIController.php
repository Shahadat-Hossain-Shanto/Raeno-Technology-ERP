<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserAPIController extends Controller
{
    public function employeeList(Request $request, $subscriberId){
        $employees = User::where('subscriber_id', $subscriberId)->get();

        return response() -> json([
            'status'=>200,
            'employees' => $employees
        ]);
    }
}
