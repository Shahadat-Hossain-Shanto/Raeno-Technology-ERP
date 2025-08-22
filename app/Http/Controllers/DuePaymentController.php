<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Session;
use App\Models\User;
use App\Models\Order;
use App\Models\Client;
use App\Models\Deposit;

use App\Models\Payment;
use App\Models\Supplier;
use App\Models\DuePayment;
use Illuminate\Http\Request;

class DuePaymentController extends Controller
{
    public function store(Request $request){

        Session::put('subscriberId', $request->subscriberId);

        $duePayment = new DuePayment;

        $users = User::where('contact_number', $request->userId)
                    ->orWhere('email', $request->userId)
                    ->get();

        foreach($users as $user){
            $userName =  $user->name;
        }

        // Log::info($request->mobile);

        if($request->type == 'customer'){
            if((int)$request->id == 0){

                $checkClientMobile = Client::where('mobile', '=', $request->mobile)->get();

                // Log::info($checkClientMobile);

                if($checkClientMobile->isEmpty()){
                    $newClient = new Client;
                    $newClient->name = $request->name;
                    $newClient->mobile = $request->mobile;
                    // $newClient->storeId = $request->storeId;
                    $newClient->subscriber_id = $request->subscriberId;
                    $newClient->save();
                }else{
                    return response() -> json([
                        'status'=>200,
                        'message' => 'Client Mobile Already Exists!'
                    ]);
                }
                $duePayment->total              = doubleval($request->paid_amount) + doubleval($request->due_amount);
                $duePayment->paid_amount        = $request->paid_amount;
                $duePayment->due_amount         = $request->due_amount;
                $duePayment->payment_method     = $request->payment_method;
                if($request->payment_method == 'cash'){
                    $duePayment->cash               = $request->paid_amount;
                }else if($request->payment_method == 'card'){
                    $duePayment->card               = $request->paid_amount;
                }else{
                    $duePayment->mobile_bank        = $request->paid_amount;
                }
                $duePayment->clientId           = $newClient->id;
                $duePayment->subscriber_id      = (int)$request->subscriberId;
                $duePayment->salesBy            = (int)$request->userId;
                $duePayment->salesBy_name       = $userName;
                $duePayment->store_id           = (int)$request->storeId;
                $duePayment->image              = $request->image;
                $duePayment->note               = $request->note;

                $depositDate                = strtotime($request->depositDate);
                $duePayment->deposit_date      = date('Y-m-d', $depositDate);
                // $duePayment->orderId               = $request->orderId;



                // $duePayment->save();


                $deposit = new Deposit;
                $depositDate                = strtotime($request->depositDate);
                $deposit->deposit_date      = date('Y-m-d', $depositDate);
                $deposit->due               = $request->due_amount;
                $deposit->balance           = $request->due_amount;
                $deposit->status            = 'due';
                $deposit->client_id         = $newClient->id;
                $deposit->store_id          = (int)$request->storeId;
                $deposit->salesBy_id        = (int)$request->userId;
                $deposit->salesBy_name      = $userName;
                $deposit->subscriber_id     = (int)$request->subscriberId;
                $deposit->save();

            }else{

                if ($request->order_id != 0) {
                    $order = new Order;

                    $order->orderId         = $request->order_id;
                    $order->clientId        = (int)$request->id;
                    $order->total           = $request->total;
                    $order->totalDiscount   = 0;
                    $order->grandTotal      = $request->total;
                    $orderDateStr           = strtotime($request->depositDate);
                    $order->orderDate       = date('Y-m-d', $orderDateStr);
                    $order->totalTax        = 0;
                    $order->created_by      = $request->userId;
                    $order->subscriber_id   = (int)$request->subscriberId;
                    $order->store_id        = (int)$request->storeId;
                    $order->salesBy_id      = 0;
                    $order->pos_id          = 0;
                    $order->specialDiscount = 0;
                    $order->save();

                    $payment = new Payment;
                    $payment->paymentId     = 0;
                    $payment->amount        = $request->total;
                    $payment->type          = 'Manual';
                    $payment->created_by    = $request->userId;
                    $payment->orderId       = $order->id;
                    $payment->clientId      = (int)$request->id;
                    $payment->subscriber_id = (int)$request->subscriberId;
                    $payment->total         = $request->total;
                    $payment->due           = doubleval($request->due_amount);

                    if ($request->payment_method == 'cash') {

                        $payment->cash     = $request->paid_amount;
                    } elseif ($request->payment_method == 'card') {

                        $payment->card     = $request->paid_amount;
                    } else {
                        $payment->mobile_bank     = $request->paid_amount;
                        $payment->mobile_bank_type     = $request->payment_method;
                    }
                    $payment->save();
                }

                // $duePayment->total          = $request->total;
                // $duePayment->cash           = $request->cash;
                // $duePayment->card           = $request->card;
                // $duePayment->mobile_bank    = $request->mobile_bank;
                // $duePayment->due_amount     = $request->due_amount;
                // $duePayment->clientId       = $request->id;
                // $duePayment->subscriber_id  = (int)$request->subscriberId;
                // $duePayment->salesBy        = (int)$request->userId;
                // $duePayment->salesBy_name   = $userName;
                // $duePayment->image          = $request->image;
                // $duePayment->store_id       = (int)$request->storeId;

                // $duePayment->total              = doubleval($request->paid_amount) + doubleval($request->due_amount);
                // $duePayment->cash               = $request->cash;
                // $duePayment->card               = $request->card;
                // $duePayment->mobile_bank        = $request->mobile_bank;
                $duePayment->total              = $request->total;
                $duePayment->paid_amount        = $request->paid_amount;
                $duePayment->due_amount         = $request->due_amount;
                $duePayment->payment_method     = $request->payment_method;
                if($request->payment_method == 'cash'){
                    $duePayment->cash               = $request->paid_amount;
                }else if($request->payment_method == 'card'){
                    $duePayment->card               = $request->paid_amount;
                }else{
                    $duePayment->mobile_bank        = $request->paid_amount;
                }
                $duePayment->clientId           = $request->id;
                $duePayment->subscriber_id      = (int)$request->subscriberId;
                $duePayment->salesBy            = (int)$request->userId;
                $duePayment->salesBy_name       = $userName;
                $duePayment->store_id           = (int)$request->storeId;
                $duePayment->image              = $request->image;
                $duePayment->note               = $request->note;

                $depositDate                = strtotime($request->depositDate);
                $duePayment->deposit_date      = date('Y-m-d', $depositDate);
                // $duePayment->orderId               = $request->orderId;

                if($request->due_amount != 0.0)
                {
                    $duePayment->save();
                }



                // $dueLists = DB::table("due_payments")
                //     ->select(DB::raw("SUM(due_amount) as due_amount"), "clientId",)
                //     ->where([
                //         ['clientId', (int)$request->id],
                //         ['subscriber_id', (int)$request->subscriberId],
                //         ['store_id', (int)$request->storeId]
                //     ])
                //     ->groupBy("clientId")
                //     ->get();

                // $totalDue = 0;

                // foreach($dueLists as $dueList){

                //     $totalDue = $dueList->due_amount;

                // }
                // Log::info(doubleval($totalDue));
                // Log::info(doubleval($request->due_amount));
                // Log::info((doubleval($totalDue)) + (doubleval($request->due_amount)));

                // if ($request->paid_amount!=0)
                {
                    $deposit = new Deposit;
                $depositDate                = strtotime($request->depositDate);
                $deposit->deposit_date      = date('Y-m-d', $depositDate);

                $paidAmount = doubleval($request->paid_amount);

                $deposit->due               = $request->due_amount;
                $deposit->deposit           = $paidAmount;
                $deposit->deposit_type      = 'Manual';
                $clientId = (int)$request->id;
                // log::info($clientId);
                $lastBalance = DB::table('deposits')->where('client_id', $clientId)->orderBy('id', 'desc')->limit(1)->first();


            //     if($request->total==0){
            //     if($lastBalance === null){
            //         $lastBalance = 0;
            //         $deposit->balance           = $lastBalance + $paidAmount;
            //     }
            //     else
            //     {
            //         $deposit->balance           = $lastBalance->balance + $paidAmount;
            //     }
            //     $deposit->status            = 'WEB deposit';
            // }
            // else if($request->paid_amount > $request->total)
            // {
                if ($lastBalance === null) {
                    $lastBalance = 0;
                    $deposit->balance           = $lastBalance + $paidAmount - $request->total;
                } else {
                        $deposit->balance           = $lastBalance->balance + $paidAmount - $request->total;
                    }
                $deposit->status            = 'App deposit';
                if($request->total>0)
                {
                    $deposit->order_id          = $order->orderId;
                }
            }
                // else{
                //     $deposit->status            = 'App Sale Payment';
                // $deposit->order_id          = $order->orderId;
                //         if ($lastBalance === null){
                //             $deposit->balance           = 0;
                //         }
                //         else{
                //             $deposit->balance           = $lastBalance->balance;
                //         }
                // }

                $deposit->client_id         = $request->id;
                $deposit->store_id          = (int)$request->storeId;
                $deposit->salesBy_id        = (int)$request->userId;
                $deposit->salesBy_name      = $userName;
                $deposit->subscriber_id     = (int)$request->subscriberId;

                $deposit->save();
            }

                // if($request->due_amount!=0)
                // {
                //     $deposit = new Deposit;
                //     $depositDate                = strtotime($request->depositDate);
                //     $deposit->deposit_date      = date('Y-m-d', $depositDate);
                //     $deposit->due               = $request->due_amount;
                //     $deposit->deposit_type      = 'Due';
                //     $clientId = (int)$request->id;
                //     // log::info($clientId);
                //     $lastBalance = DB::table('deposits')->where('client_id', $clientId)->orderBy('id', 'desc')->limit(1)->first();
                //     // log::info(json_encode($lastBalance));

                //     $dueAmount = doubleval($request->due_amount);

                //     if ($lastBalance === null) {
                //         $lastBalance = 0;
                //         $deposit->balance           = $lastBalance - $request->due_amount;
                //     } else {
                //         $deposit->balance           = $lastBalance->balance - $request->due_amount;
                //     }

                //     $deposit->status            = 'App Sale Payment';
                //     $deposit->client_id         = $request->id;
                //     $deposit->store_id          = (int)$request->storeId;
                //     $deposit->order_id          = $order->orderId;
                //     $deposit->salesBy_id        = (int)$request->userId;
                //     $deposit->salesBy_name      = $userName;
                //     $deposit->subscriber_id     = (int)$request->subscriberId;

                //     $deposit->save();
                // }


                // if($lastBalance === null){
                //     $lastBalance = 0;
                //     $deposit->balance           = $lastBalance + $dueAmount;
                // }else{
                //     $deposit->balance           = $lastBalance->balance + $dueAmount -(doubleval($paidAmount)) ;

                // }

                // $deposit->balance           = (doubleval($totalDue)) + (doubleval($request->due_amount));

                // $duePayment->save();
            // }
        }else{
            // Log::info($request);

            if((int)$request->id == 0){

                $checkSupplierMobile = Supplier::where('mobile', '=', $request->mobile)->get();

                // Log::info($request->name);

                if($checkSupplierMobile->isEmpty()){
                    $newSupplier = new Supplier;
                    $newSupplier->name = $request->name;
                    $newSupplier->mobile = $request->mobile;
                    // $newSupplier->storeId = $request->storeId;
                    $newSupplier->subscriber_id = $request->subscriberId;
                    $newSupplier->save();
                }else{
                    return response() -> json([
                        'status'=>200,
                        'message' => 'Supplier Mobile Already Exists!'
                    ]);
                }

                // $duePayment->total          = $request->total;
                // $duePayment->cash           = $request->cash;
                // $duePayment->card           = $request->card;
                // $duePayment->mobile_bank    = $request->mobile_bank;
                // $duePayment->due_amount     = $request->due_amount;
                // $duePayment->supplierId     = $newSupplier->id;
                // $duePayment->subscriber_id  = (int)$request->subscriberId;
                // $duePayment->salesBy        = (int)$request->userId;
                // $duePayment->salesBy_name   = $userName;
                // $duePayment->image          = $request->image;
                // $duePayment->store_id       = (int)$request->storeId;

                // $duePayment->save();

                $duePayment->total              = doubleval($request->paid_amount) + doubleval($request->due_amount);
                // $duePayment->cash               = $request->cash;
                // $duePayment->card               = $request->card;
                // $duePayment->mobile_bank        = $request->mobile_bank;
                $duePayment->paid_amount        = $request->paid_amount;
                $duePayment->due_amount         = $request->due_amount;
                $duePayment->payment_method     = $request->payment_method;
                if($request->payment_method == 'cash'){
                    $duePayment->cash               = $request->cash;
                }else if($request->payment_method == 'card'){
                    $duePayment->card               = $request->card;
                }else{
                    $duePayment->mobile_bank        = $request->mobile_bank;
                }
                $duePayment->supplierId         = $newSupplier->id;
                $duePayment->subscriber_id      = (int)$request->subscriberId;
                $duePayment->salesBy            = (int)$request->userId;
                $duePayment->salesBy_name       = $userName;
                $duePayment->store_id           = (int)$request->storeId;
                $duePayment->image              = $request->image;
                $duePayment->note               = $request->note;
                $depositDate                = strtotime($request->depositDate);
                $duePayment->deposit_date      = date('Y-m-d', $depositDate);
                // $duePayment->orderId               = $request->orderId;


                $duePayment->save();

                $deposit = new Deposit;
                $depositDate                = strtotime($request->depositDate);
                $deposit->deposit_date      = date('Y-m-d', $depositDate);
                $deposit->due               = $request->due_amount;
                $deposit->balance           = $request->due_amount;
                $deposit->status            = 'due';
                $deposit->supplier_id       = $newSupplier->id;
                $deposit->store_id          = (int)$request->storeId;
                $deposit->salesBy_id        = (int)$request->userId;
                $deposit->salesBy_name      = $userName;
                $deposit->subscriber_id     = (int)$request->subscriberId;
                $deposit->save();

            }else{
                // $duePayment->total          = $request->total;
                // $duePayment->cash           = $request->cash;
                // $duePayment->card           = $request->card;
                // $duePayment->mobile_bank    = $request->mobile_bank;
                // $duePayment->due_amount     = $request->due_amount;
                // $duePayment->supplierId     = $request->id;
                // $duePayment->subscriber_id  = (int)$request->subscriberId;
                // $duePayment->salesBy        = (int)$request->userId;
                // $duePayment->salesBy_name   = $userName;
                // $duePayment->image          = $request->image;
                // $duePayment->store_id       = (int)$request->storeId;

                $duePayment->total              = doubleval($request->paid_amount) + doubleval($request->due_amount);
                // $duePayment->cash               = $request->cash;
                // $duePayment->card               = $request->card;
                // $duePayment->mobile_bank        = $request->mobile_bank;
                $duePayment->paid_amount        = $request->paid_amount;
                $duePayment->due_amount         = $request->due_amount;
                $duePayment->payment_method     = $request->payment_method;
                if($request->payment_method == 'cash'){
                    $duePayment->cash               = $request->cash;
                }else if($request->payment_method == 'card'){
                    $duePayment->card               = $request->card;
                }else{
                    $duePayment->mobile_bank        = $request->mobile_bank;
                }
                $duePayment->supplierId         = $request->id;
                $duePayment->subscriber_id      = (int)$request->subscriberId;
                $duePayment->salesBy            = (int)$request->userId;
                $duePayment->salesBy_name       = $userName;
                $duePayment->store_id           = (int)$request->storeId;
                $duePayment->image              = $request->image;
                $duePayment->note               = $request->note;

                $depositDate                = strtotime($request->depositDate);
                $duePayment->deposit_date      = date('Y-m-d', $depositDate);

                // $duePayment->orderId               = $request->orderId;


                $deposit = new Deposit;
                $depositDate                = strtotime($request->depositDate);
                $deposit->deposit_date      = date('Y-m-d', $depositDate);
                $deposit->due               = $request->due_amount;

                // $dueLists = DB::table("due_payments")
                //     ->select(DB::raw("SUM(due_amount) as due_amount"), "supplierId",)
                //     ->where([
                //         ['supplierId', (int)$request->id],
                //         ['subscriber_id', (int)$request->subscriberId],
                //         ['store_id', (int)$request->storeId]
                //     ])
                //     ->groupBy("supplierId")
                //     ->get();

                // $totalDue = 0;

                // foreach($dueLists as $dueList){

                //     $totalDue = $dueList->due_amount;

                // }
                // Log::info(doubleval($totalDue));
                // Log::info(doubleval($request->due_amount));
                // Log::info((doubleval($totalDue)) + (doubleval($request->due_amount)));

                $clientId = (int)$request->id;
                $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();

                // Log::info($lastBalance->balance);
                $dueAmount = doubleval($request->due_amount);

                if($lastBalance === null){
                    $lastBalance = 0;
                    $deposit->balance           = $lastBalance + $dueAmount;
                }else{
                    $deposit->balance           = $lastBalance->balance + $dueAmount;

                }

                // $deposit->balance           = (doubleval($totalDue)) + (doubleval($request->due_amount));
                $deposit->status            = 'due';
                $deposit->client_id         = $request->id;
                $deposit->store_id          = (int)$request->storeId;
                $deposit->salesBy_id        = (int)$request->userId;
                $deposit->salesBy_name      = $userName;
                $deposit->subscriber_id     = (int)$request->subscriberId;

                $deposit->save();
                $duePayment->save();
            }
        }


        return response() -> json([
            'status'=>200,
            'message' => 'Due Payment added Successfully!'
        ]);



    }

    public function list(Request $request, $subscriberId, $storeId){

        // $duePayments = DuePayment::where('subscriber_id', 1)->get();

        $clients = DB::table("due_payments")
                    ->select("clientId")
                    ->where([
                        ['subscriber_id', $subscriberId],
                        ['store_id', $storeId]
                    ])
                    ->groupBy("clientId")
                    ->take(5)
                    ->get();

        // Log::info();
        $totalStoreDue = 0;


        foreach($clients as $client){
            $clientId = $client->clientId;

            if($clientId != 0){
                $client = Client::find($clientId);
                $dueLists = Deposit::where([
                    ['client_id', $clientId],
                    ['subscriber_id', $subscriberId],
                    ['store_id', $storeId]
                ])
                ->get();

                // Log::info($dueLists);

                $payment_arr = [];
                $totalDue = 0;

                foreach($dueLists as $dueList){

                    $totalDue = $totalDue + $dueList->due;
                    $storeId = $dueList->store_id;


                    // $payment = [
                        // 'total' => $dueList->total,
                        // 'due' => $dueList->due_amount,
                        // 'SalesBy' => $dueList->salesBy,
                        // 'SalesByName' => $dueList->salesBy_name,
                        // 'created_at' => date($dueList->created_at)
                    // ];

                    // $payment_arr[] = $payment;
                }

                $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();

                if($lastBalance){
                    $balance           = $lastBalance->balance;
                }else{
                    $balance = 0;
                }


                $totalStoreDue = $totalStoreDue +  $totalDue;

                $clientData = [
                    'clientId' => $client->id,
                    'clientName' => $client->name,
                    'mobile' => $client->mobile,
                    'type' => $client->type,
                    'email' => $client->email,
                    'address' => $client->address,
                    'note' => $client->note,
                    'storeId' => $client->storeId,
                    'image' => $client->image,
                    'subscriber_id' => $client->subscriber_id,
                    // 'payments' => $payment_arr
                    'totalDue' => $balance,

                ];

                $client_arr[] = $clientData;

            }

        }



        if (isset($client_arr)){
            return response() -> json([
                "status" => "success",
                "statusCode" => 200,
                "message" => "Data found",
                "role" => null,
                'totalStoreDue' => $totalStoreDue,
                'storeId' => $storeId,
                "data" => $client_arr,
            ]);
        }

        //     $payment = [
        //             'total' => $duePayment->total,
        //             'due' => $duePayment->due_amount,
        //             'SalesBy' => $duePayment->salesBy,
        //             'SalesByName' => $duePayment->salesBy_name,
        //             'created_at' => date($duePayment->created_at)
        //         ];

        //     // $payment_arr[] = $payment;

        //     $due = [
        //         'clientName' => $client->name,
        //         'mobile' => $client->mobile,
        //         'type' => $client->type,
        //         'email' => $client->email,
        //         'address' => $client->address,
        //         'note' => $client->note,
        //         'storeId' => $client->storeId,
        //         'image' => $client->image,
        //         'subscriber_id' => $client->subscriber_id,
        //         'payments' => $payment
        //     ];

        //     $due_array[] = $due;

        //     // foreach($dueLists as $dueList){



        // return response() -> json([
        //     "status" => "success",
        //     "statusCode" => 200,
        //     "message" => "Data found",
        //     "role" => null,
        //     "data" => $due_array,
        // ]);
    }

    public function dueList($subscriberId, $storeId, $clientId){

        $client = Client::find($clientId);
        $depositList = Deposit::where([
                    ['subscriber_id', $subscriberId],
                    ['store_id', $storeId],
                    ['client_id', $clientId]
                ])->get();

        $totalDue = 0;
        foreach($depositList as $deposit){

            $totalDue = $totalDue + $deposit->due;

            $payment = [
                'depositDate' => $deposit->deposit_date,
                'total' => $deposit->total,
                'due' => $deposit->due,
                'deposit' => $deposit->deposit,
                'balance' => $deposit->balance,
                'status' => $deposit->status,
                'deposit_type' => $deposit->deposit_type,
                'note' => $deposit->note,
                'image' => $deposit->image,
                'clientId' => $deposit->client_id,
                'storeId' => $deposit->store_id,
                'SalesById' => $deposit->salesBy_id,
                'SalesByName' => $deposit->salesBy_name,
                'subscriberId' => $deposit->subscriber_id,
                'created_at' => date($deposit->created_at)
            ];

            $payment_arr[] = $payment;
        }


        $due = [
            'clientName' => $client->name,
            'mobile' => $client->mobile,
            'type' => $client->type,
            'email' => $client->email,
            'address' => $client->address,
            'note' => $client->note,
            'storeId' => $client->storeId,
            'image' => $client->image,
            'subscriber_id' => $client->subscriber_id,
            'totalDue' => $totalDue,
            'payments' => $payment_arr
        ];

        return response() -> json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $due,
        ]);
    }
}
