<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DuePayment;
use App\Models\Client;
use App\Models\Order;
use App\Models\Payment;

use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class CustomerCreditController extends Controller
{
    public function indexView(){
        return view('customer/customer-credit');
    }

    public function index(Request $request){

        // $duePayment = DB::table("clients")
        //     ->join('due_payments', 'clients.id', '=', 'due_payments.clientId')
        //     ->select(DB::raw("SUM(due_payments.due_amount) as td"), "due_payments.clientId", "clients.name")
        //     ->where([
        //         ['due_payments.subscriber_id', '=', Auth::user()->subscriber_id ]
        //     ])
        //     ->groupBy("due_payments.clientId", "clients.name")
        //     ->get();

        // Log::info($duePayment);

        $duePayment = DB::table("clients")
            ->join('deposits', 'clients.id', '=', 'deposits.client_id')
            ->select(DB::raw("SUM(deposits.due) as totalDue"), DB::raw("SUM(deposits.deposit) as totalDeposit"),DB::raw("SUM(deposits.balance) as totalBalance"), "deposits.client_id", "clients.name", "clients.mobile", "deposits.deposit")
            ->where([
                ['deposits.subscriber_id', '=', Auth::user()->subscriber_id]
            ])
            ->groupBy("deposits.client_id", "clients.name")
            // ->orderBy("deposits.created_at", "desc")
            ->get();

        $purchaseDeposit = DB::table("clients")
        ->join('deposits', 'clients.id', '=', 'deposits.client_id')
        ->select(DB::raw("SUM(deposits.deposit) as Deposit"), "deposits.client_id",)
        ->where([
            ['deposits.subscriber_id', '=', Auth::user()->subscriber_id],
            ['deposits.status', '=', 'WEB deposit']
        ])
        ->groupBy("deposits.client_id", "clients.name")
        // ->orderBy("deposits.created_at", "desc")
        ->get();

        // Log::info(json_encode($duePayment));
        if($duePayment->isEmpty()){
            return response()->json([
                'message' => 'No data found.',
            ]);
        }else{

            foreach($duePayment as $dueP){
                $lastBalance = DB::table('deposits')->where('client_id', $dueP->client_id)->latest()->first();

                $x = [
                    "totalDue" => $dueP->totalDue,
                    "totalDeposit" => $dueP->totalDeposit,
                    "totalPurchase"=> $dueP->totalDue+$dueP->totalDeposit,
                    "client_id" => $dueP->client_id,
                    "name" => $dueP->name,
                    "mobile"=>$dueP->mobile,
                    "balance" => $lastBalance->balance,
                ];

                foreach ($purchaseDeposit as $purdep) {
                    if($dueP->client_id==$purdep->client_id && $purdep->client_id==$x['client_id'])
                    {
                        $x["totalPurchase"]= $dueP-> totalDue + $dueP->totalDeposit- $purdep->Deposit;
                    }

                }
                $data[] = $x;
            }
            Log::info( json_encode( $data));
        }
        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'due'=>$data,
                // 'due' => $duePayment,
            ]);
        }


    }
    public function orderDetails($orderId)
    {
        $order = Order::where("orderId", $orderId)->first();
        Log::info($order);
        $payment=Payment::where("orderId", $order->id)->first();
        Log::info($payment);

        $client = Client::find($payment->clientId);

        return view('customer/customer-order-details', ['payment' => $payment, 'client' => $client, 'order' => $order]);
    }
}
