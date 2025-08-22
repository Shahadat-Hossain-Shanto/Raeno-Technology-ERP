<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $roles  = Role::where('subscriber_id', Auth::user()->subscriber_id)->get();
        //$users = User::all();
        //$users = User::where('subscriber_id', Auth::user()->subscriber_id)->get();
        if ($request->ajax()) {
            return response()->json([
                'status' => 200,
                'user' => $user,

            ]);
        }
        return view('user.edit-profile', compact('user', 'roles', 'stores'));
    }

    public function resetPassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return view('user.reset-password',['user'=>$user]);
    }

    public function updateProfile(Request $request, $id)
    {
        // Create New User
        $user = User::find($id);
        $messages = [
            'name.required'  =>    "Name is required.",
            'name.max'  =>    "Max 255 characters.",
            'email.required'  =>    "Email is required.",
            'email.email'  =>    "Email is not valid.",
            'email.max'  =>    "Max 255 characters.",
            'contactnumber.required'  =>    "Contact number is required.",
            // 'password.required'  =>    "Password is required.",
            'password.confirmed'  =>    "Confirm your password or password does not match.",
        ];



        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contactnumber' => ['required'],
            // 'password' => ['required', 'confirmed'],
            'password' => ['confirmed'],
        ], $messages);

        if ($validator->passes()) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact_number = $request->contactnumber;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully!'
            ]);
        }

        return response()->json(['error' => $validator->errors()]);
    }
    public function updatePassword(Request $request, $id)
    {
        // Create New User
        $user = User::find($id);
        $messages = [
            // 'password.required'  =>    "Password is required.",
            'password.confirmed'  =>    "Confirm your password or password does not match.",
        ];



        $validator = Validator::make($request->all(), [
            // 'password' => ['required', 'confirmed'],
            'password' => ['confirmed'],
        ], $messages);

        if ($validator->passes()) {
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully!'
            ]);
        }

        return response()->json(['error' => $validator->errors()]);
    }
}
