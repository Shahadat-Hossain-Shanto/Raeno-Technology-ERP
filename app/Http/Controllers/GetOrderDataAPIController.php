<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetOrderDataAPIController extends Controller
{
    public function salesList(Request $request, $subscriberId, $storeId){
        $clients = DB::table('clients')
        ->select('id')
        ->where('subscriber_id', $subscriberId)
        ->get();

        $totalDue = 0;
        foreach ($clients as $client) {
            $totalDues = DB::table('deposits')
            ->select('balance')
            ->where([
                ['subscriber_id', $subscriberId],
                ['client_id', $client->id],
                ['store_id', $storeId],
            ])
            ->orderBy('created_at', 'desc')->limit(1)->first();
            if ($totalDues != null) {
                $totalDue = $totalDue + $totalDues->balance;
            }
        }
        if ($totalDue < 0) {
            $totalDue = -1 * $totalDue;
        } else {
            $totalDue = 0;
        }
        $totalpays = DB::table('deposits')
            ->select(DB::raw("SUM(deposit) as paid"))
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();

        $paid =0;
        if($totalpays[0]->paid!=null){$paid=$totalpays[0]->paid;}
        $data =(object)[
            'paid_amount'=> $paid,
            'due_amount'=> $totalDue,
        ];

            return response()->json([
                'status'=>200,
                'data'=> $data,
            ]);
    }
    
    public function salesListDetails(Request $request,$startdate, $enddate, $subscriberId, $storeId){
        $data = DB::table("orders")->leftjoin('clients', 'clients.id', 'orders.clientId')
                    ->join('payments', 'orders.id', 'payments.orderId')
                    ->whereBetween('orders.orderDate', [$startdate, $enddate])
                    ->where([
                        ['orders.subscriber_id', $subscriberId],
                        ['orders.store_id', $storeId]
                    ])
                    ->orderBy('orders.id', 'desc')
                    ->get(['orders.orderId', 'orders.grandTotal', 'orders.orderDate', 'clients.name', 'clients.mobile', 'payments.total', 'payments.due', DB::raw("payments.total-payments.due as paid")]);
        return response()->json([
            'status'=>200,
            'data'=>$data,
        ]);
    }
}
