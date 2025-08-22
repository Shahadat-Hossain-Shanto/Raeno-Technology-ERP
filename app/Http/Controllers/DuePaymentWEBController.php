<?php

namespace App\Http\Controllers;

use App\Models\DuePayment;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Supplier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class DuePaymentWEBController extends Controller
{
    public function store(Request $request){

        $subscriberId = Auth::user()->subscriber_id;

        $users = User::where('contact_number', $request->userId)
                    ->orWhere('email', $request->userId)
                    ->get();

        foreach($users as $user){
            $userName =  $user->name;
        }

        $duePayment = new DuePayment;
        $duePayment->total          = doubleval($request->total);

        if($request->cash == ""){
            $cash = 0;
        }else{
            $cash = doubleval($request->cash);
            $paymentType = 'cash';
            $duePayment->payment_method = $paymentType;
        }
        if($request->card == ""){
            $card = 0;
        }else{
            $card = doubleval($request->card);
            $paymentType = 'card';
            $duePayment->payment_method = $paymentType;
        }
        if($request->mobile_bank == "" || $request->mobile_bank == 'Select'){
            $mobile_bank = 0;
        }else{
            $mobile_bank = doubleval($request->mobile_bank);
            $paymentType = $request->mobile_bank_type;
            $duePayment->payment_method = $paymentType;
        }

        // if($request->mobile_bank == ""){
        //     $mobile_bank = 0;
        // }else{
        //     $mobile_bank = $request->mobile_bank;
        // }

        $duePayment->cash           = $cash;
        $duePayment->card           = $card;
        $duePayment->mobile_bank    = $mobile_bank;
        $duePayment->paid_amount    = $request->paid_amount;
        $duePayment->due_amount     = doubleval($request->due_amount);
        // $duePayment->payment_method = $paymentType;
        $duePayment->clientId       = (int)$request->clientId;
        $duePayment->subscriber_id  = $subscriberId;
        $duePayment->salesBy        = (int)$request->userId;
        $duePayment->salesBy_name   = $userName;
        $duePayment->store_id       = (int)$request->storeId;
        $duePayment->note           = 'From Web POS';
        // $duePayment->image          = $request->image;
        $depositDate                = strtotime($request->depositDate);
        $duePayment->deposit_date   = date('Y-m-d', $depositDate);
        // $duePayment->orderId    = $request->orderId;

        $duePayment->save();

        // $deposit = new Deposit;
        // $depositDate                = strtotime($request->depositDate);
        // $deposit->deposit_date      = date('Y-m-d', $depositDate);

        // $clientId = (int)$request->clientId;

        // $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();

        // // Log::info($lastBalance->balance);
        // $dueAmount = doubleval($request->due_amount);

        // if($lastBalance === null){
        //     // $lastBalance = 0;
        //     $deposit->balance           = $dueAmount;
        // }else{
        //     $deposit->balance           = $lastBalance->balance - $request->due_amount;

        // }

        // // $dueAmount = doubleval($request->due_amount);

        // // $deposit->balance           = $lastBalance->balance + $dueAmount;
        // $deposit->due               = $request->due_amount;
        // // $deposit->balance           = $lastBalance + doubleval($request->due_amount);
        // $deposit->status            = 'due';
        // $deposit->client_id         = (int)$request->clientId;
        // $deposit->store_id          = (int)$request->storeId;
        // $deposit->order_id          = (int)$request->orderId;
        // $deposit->salesBy_id        = (int)$request->userId;
        // $deposit->salesBy_name      = $userName;
        // $deposit->subscriber_id     = $subscriberId;
        // $deposit->save();

        return response() -> json([
            'status'=>200,
            'message' => 'Due Payment added Successfully!'
        ]);
    }
}
