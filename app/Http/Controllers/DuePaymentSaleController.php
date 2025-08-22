<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Session;
use App\Models\User;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\Supplier;

use App\Models\DuePayment;
use Illuminate\Http\Request;

class DuePaymentSaleController extends Controller
{
    public function store(Request $request){

        // Log::info("Due Sale API");

        Session::put('subscriberId', $request->subscriberId);
        $user = User::where('contact_number', $request->userId)
        ->orWhere('email', $request->userId)
        ->first();

        $client = Client::where('id', $request->clientId)->first();

        $userName =  $user->name;
        $clientId = $request->clientId;
        // $clientName = $client->name;
        // $clientMobile = $client->mobile;

        $totalPaid = doubleval($request->cash) + doubleval($request->card) + doubleval($request->mobile_bank);
        $totalDue = doubleval($request->due_amount);
        $total = $totalPaid + $totalDue;
        $cash = doubleval($request->cash);
        $card = doubleval($request->card);
        $mobileBank = doubleval($request->mobile_bank);
        $date = strtotime($request->date);

        // Log::info($totalDue);


        $duePayment = new DuePayment;
        $duePayment->total              = $total;
        $duePayment->paid_amount        = $totalPaid;
        $duePayment->due_amount         = $totalDue;
        $duePayment->payment_method     = "App sale mix payment";
        $duePayment->cash               = $cash;
        $duePayment->card               = $card;
        $duePayment->mobile_bank        = $mobileBank;
        $duePayment->clientId           = $clientId;
        $duePayment->subscriber_id      = (int)$request->subscriberId;
        $duePayment->salesBy            = $request->userId;
        $duePayment->salesBy_name       = $userName;
        $duePayment->store_id           = (int)$request->storeId;
        $duePayment->note               = "App Sale Due";
        $duePayment->deposit_date      = date('Y-m-d', $date);



        // $deposit = new Deposit;
        // $deposit->deposit_date      = date('Y-m-d', $date);
        // $deposit->due               = $request->due_amount;

        // $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();

        // $dueAmount = $totalDue;

        // if($lastBalance === null){
        //     $lastBalance = 0;
        //     $deposit->balance           = $lastBalance - $dueAmount;
        // }else{
        //     $deposit->balance           = $lastBalance->balance - $dueAmount;
        // }

        // $deposit->status            = 'due';
        // $deposit->deposit_type      = 'Mix Type';
        // $deposit->note              = 'App Due';
        // $deposit->client_id         = $clientId;
        // $deposit->store_id          = (int)$request->storeId;
        // $deposit->salesBy_id        = $request->userId;
        // $deposit->salesBy_name      = $userName;
        // $deposit->subscriber_id     = (int)$request->subscriberId;

        // $deposit->save();
        $duePayment->save();


        return response() -> json([
            'status'=>200,
            'message' => 'Due Payment Sale added Successfully!'
        ]);

    }
}
