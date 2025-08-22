<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Store;
use App\Models\Pos;
use App\Models\User;
use App\Models\Subscriber;
use Carbon\Carbon;

use Session;
use Log;

class HomeController extends Controller
{
    public function index(){
        // return view('layouts/master');

        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();
        $monthName = Carbon::createFromFormat('Y-m-d', $date)->month;

        $users = Subscriber::join("users", "subscribers.id","=", "users.subscriber_id")
                                ->where("users.id", Auth::user()->id)
                                ->get();

        foreach($users as $user){
            $subscriberId = $user->subscriber_id;
        }

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // Log::info($stores);

        foreach($stores as $store){
            $storeId = $store->id;
        }

        $poses = Pos::where('store_id', $storeId)->get();

        $subscriberId = Auth::user()->subscriber_id;

        $subscriber = Subscriber::find($subscriberId);
        $orgName = $subscriber->org_name;
        $orgLogo = $subscriber->logo;
        Session::put('orgNameMaster', $orgName);
        Session::put('orgLogo', $orgLogo);

        return view('home', ['stores' => $stores, 'poses' => $poses, 'monthName' => $monthName ]);

        // return $store;

    }


}
