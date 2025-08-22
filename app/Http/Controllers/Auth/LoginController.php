<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\Models\Pos;
use App\Models\User;
use App\Models\Store;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function authenticate(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();

    //         return redirect()->intended('dashboard');
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    public function login(Request $request)
    {
        $input = $request->all();

        // $attributeNames = array(
        //     'contactnumber' => 'Email or Phone',
        //     'password' => 'Password',
        // );

        $messages = [
            'username.required'    =>   "Username is required!",
            'password.required'         =>    "Password is required!",
        ];

        // $rules = [
        //     'contactnumber' => 'required',
        //     'password' => 'required',

        // ];

        // $validate  = Validator::make ( $request->all(), $rules, $messages );
        // $validator->setAttributeNames($attributeNames);

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ], $messages);

        $fieldType = filter_var($request->contactnumber, FILTER_VALIDATE_EMAIL) ? 'username' : 'username';
        if (auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
            // if ($request->contactnumber == 'admin@gmail.com') {
            //     return redirect()->route('subscriber');
            // }
            return redirect()->route('home');
        } else {
            // return redirect()->route('login')
            //     ->with('errorMsg','The provided credentials do not match our records.');

            return back()->withErrors([
                'msg' => 'The provided credentials do not match our records.',
            ]);
            // return redirect()->back()->withErrors([$validate->messages(), 'msg' => 'The provided credentials do not match our records.'])->withInput();
        }
    }

    public function check(Request $request)
    {
        $users = User::all();
        foreach ($users as $user) {
            if (($request->username == $user->email || $request->username == $user->contact_number) && Hash::check($request->password, $user->password)) {

                $subscriber = Subscriber::find($user->subscriber_id);
                if ($subscriber->status == 'Active') {

                    $stores = Store::where('subscriber_id', $user->subscriber_id)->get();

                    foreach ($stores as $store) {
                        $poss = Pos::where('store_id', $store->id)->get();

                        foreach ($poss as $pos) {
                            $pos = [
                                'posId' => $pos->id,
                                'posName' => $pos->pos_name,
                                'posStatus' => $pos->pos_status,
                                'posPin' => $pos->pos_pin,
                                'store_id' => $pos->store_id
                            ];

                            $pos_arr[] = $pos;
                        }

                        $stores = [
                            'storeId' => $store->id,
                            'storeName' => $store->store_name,
                            'storeAddress' => $store->store_address,
                            'contactNumber' => $store->contact_number,
                            'poses' => $pos_arr
                        ];

                        // $stores_arr[] =  $stores;
                        // $stores_arr = [];

                        $data[] = $stores;
                        $pos_arr = [];
                        // return $stores_arr;
                    }
                    if ($subscriber->package_name == null) {
                        $accountType = "basic";
                    } else {
                        $accountType = $subscriber->package_name;
                    }

                    // return $data;
                    // return response()->json($data, 200);
                    return response()->json([
                        'orgName' => $subscriber->org_name,
                        'subscriberId' => $user->subscriber_id,
                        'accountType' => $accountType,
                        'accountStatus' => $subscriber->registration_type,
                        'helpLine' => '01780-333999',
                        'code' => 200,
                        'message' => 'success',
                        'stores' => $data,
                    ]);
                } else {
                    return response()->json([
                        'code' => 400,
                        'message' => 'User Inactive',
                        'helpLine' => '01780-333999',
                    ]);
                }
            }
        }

        return response()->json([
            'code' => 400,
            'message' => 'Username or Password Invalid',
        ]);
    }
}
