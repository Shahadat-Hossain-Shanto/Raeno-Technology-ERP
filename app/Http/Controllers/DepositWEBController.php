<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Store;
use App\Models\Client;
use App\Models\Deposit;

use App\Models\DuePayment;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class DepositWEBController extends Controller
{
    public function create(){
        $clients = Client::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $methods = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('deposit/deposit-add', ['customers' => $clients, 'stores' =>$stores, 'methods' => $methods]);
    }

    public function store(Request $request){
        // log::info($request);

        $userId = Auth::user()->id;
        $userName = Auth::user()->name;
        $subscriberId = Auth::user()->subscriber_id;
        $type = 'customer';



        $messages = [
            'id.required'  =>    "Customer is required.",
            'storeId.required'  =>    "Store is required.",
            'deposit_type.required'  =>    "Deposit type is required.",
            'deposit.required'  =>    "Deposit amount is required.",
            'depositDate.required'  =>    "Deposit date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'storeId' => 'required',
            'deposit_type' => 'required',
            'deposit' => 'required',
            'depositDate' => 'required',
        ], $messages);

        if ($validator->passes()) {


            $deposit = new Deposit;

            // $depositDate                = strtotime($request->depositDate);
            // $deposit->deposit_date      = date('Y-m-d', $depositDate);

            $deposit->deposit_date      = $request->depositDate;


            $deposit->due               = 0;
            $deposit->deposit           = $request->deposit;

            if($type == "customer"){
                if($request->id != 0){
                    // $client = Client::find((int)$request->clientId);
                    $dueLists = DuePayment::where([
                        ['clientId', $request->id],
                        ['subscriber_id', $subscriberId],
                        ['store_id', $request->storeId]
                    ])
                    ->get();

                    $totalDue = 0;

                    foreach($dueLists as $dueList){

                        $totalDue = $totalDue + $dueList->due_amount;

                    }
                }else{
                    return response() -> json([
                        'status'=>200,
                        'message' => 'Customer not exist!'
                    ]);
                }

                $lastBalance = DB::table('deposits')->where('client_id', $request->id)->latest()->first();

                if($lastBalance != null){
                    $deposit->balance           = $lastBalance->balance + $request->deposit;
                }else{
                    $deposit->balance = $request->deposit;
                }

                $deposit->status            = 'WEB deposit';
                $deposit->deposit_type      = $request->deposit_type;
                $deposit->note              = $request->note;

                // $deposit->image             = $request->image;
                $deposit->client_id         = $request->id;
                $deposit->store_id          = $request->storeId;
                $deposit->salesBy_id        = $userId;
                $deposit->salesBy_name      = $userName;
                $deposit->subscriber_id     = $subscriberId;

                $deposit->save();
            }


            // $depositId = $deposit->id;

            return response() -> json([
                'status'=>200,
                'message' => 'Deposit added Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }
}
