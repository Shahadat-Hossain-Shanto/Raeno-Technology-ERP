<?php

namespace App\Http\Controllers\Auth;


use Log;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    public function loginview()
    {
        return view('auth.admin.admin-login');
    }
    public function loginstore(LoginRequest $request)
    {
        //dd($request); {
        $request->authenticate();
        //dd($request);

        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);


        // $request->authenticate();

        // $request->session()->regenerate();

        // return redirect()->intended(RouteServiceProvider::ADMIN_DASHBOARD);
    }

    public function regUser()
    {

        $roles  = Role::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        //  dd($roles);
        return view('auth.admin.create-user', ['roles' => $roles, 'stores' => $stores]);
    }

    public function storeUser(Request $request)
    {


        $messages = [
            'name.required'  =>    "Name is required.",
            'name.max'  =>    "Max 255 characters.",
            'username.required' => "Username is required.",
            'username.max'      => "Max 255 characters.",
            'address.max'       => "Max 300 characters.",
            // 'email.required'  =>    "Email is required.",
            // 'email.email'  =>    "Email is not valid.",
            'email.max'  =>    "Max 255 characters.",
            // 'email.unique'  =>    "Email already exists.",
            'contactnumber.required'  =>    "Contact number is required.",
            'roles.required'  =>    "Role is required.",
            'store.required'  =>    "Store is required.",
            'password.required'  =>    "Password is required.",
            'password.confirmed'  =>    "Confirm your password or password does not match.",
        ];



        $validator = Validator::make($request->all(), [
            'name'              => ['required', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'username'          => ['required', 'max:255', 'unique:users'],
            'address'           => ['nullable', 'max:300'],
            'contactnumber'     => ['required'],
            'roles'             => ['required'],
            'store'             => ['required'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
        ], $messages);

        if ($validator->passes()) {
            $user = new User();

            $user->name            = $request->name;
            $user->username        = $request->username;
            $user->address         = $request->address;
            $user->email           = $request->email;
            $user->password        =  Hash::make($request->password);
            $user->contact_number  = $request->contactnumber;
            $user->store_id        = $request->store;

            $user->subscriber_id                  = Auth::user()->subscriber_id;

            if ($request->roles) {
                $user->assignRole($request->roles);
            }
            $user->save();
            // Auth::login($user);
            event(new Registered($user));
            return redirect()->route('admin.roles');
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function userList()
    {
        $roles  = Role::where('subscriber_id', Auth::user()->subscriber_id)->get();
        //$users = User::all();
        $users = User::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $stores = Store::join('users, stores.id', 'users.store_id')
        //                 ->where('users.subscriber_id', Auth::user()->subscriber_id)
        //                 ->get();

        return view('auth.admin.user-index', compact('users', 'roles'));
    }



    public function userEdit(Request $request, $id)
    {


        $user = User::find($id);
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
        return view('auth.admin.user-edit', compact('user', 'roles', 'stores'));
    }


    public function userUpdate(Request $request, $id)
    {
        // Create New User
        $user = User::find($id);
        $messages = [
            'name.required'  =>    "Name is required.",
            'name.max'  =>    "Max 255 characters.",
            // 'email.required'  =>    "Email is required.",
            // 'email.email'  =>    "Email is not valid.",
            'email.max'  =>    "Max 255 characters.",
            'contactnumber.required'  =>    "Contact number is required.",
            'roles.required'  =>    "Role is required.",
            'store.required'  =>    "Store is required.",
            // 'password.required'  =>    "Password is required.",
            'password.confirmed'  =>    "Confirm your password or password does not match.",
        ];



        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contactnumber' => ['required'],
            'roles' => ['required'],
            'store' => ['required'],
            // 'password' => ['required', 'confirmed'],
            'password' => ['confirmed'],
        ], $messages);

        if ($validator->passes()) {

            // log::info($request);
            $user->name             = $request->name;
            $user->email            = $request->email;
            $user->contact_number   = $request->contactnumber;
            $user->store_id         = $request->store;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $user->roles()->detach();
            if ($request->roles) {
                $user->assignRole($request->roles);
            }


            return response()->json([
                'status' => 200,
                'message' => 'Product updated successfully!'
            ]);
            return redirect()->route('admin.roles');
        }

        return response()->json(['error' => $validator->errors()]);
    }


    public function userDestroy($id)
    {
        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        session()->flash('success', 'User has been deleted !!');
        return back();
    }

    public function destroy(Request $request)
    {

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        //dd($request);

        return redirect()->route('admin.login');
    }
}
