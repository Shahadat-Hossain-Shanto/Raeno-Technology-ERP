<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Client;

use App\Models\Supplier;

use App\Models\DuePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DueReportController extends Controller
{

    public function indexView(){
        return view('report/due-report');
    }

    public function index(Request $request){

        $duePayment = DB::table("clients")
            ->join('due_payments', 'clients.id', '=', 'due_payments.clientId')
            ->select(DB::raw("SUM(due_payments.due_amount) as td"), "due_payments.clientId", "clients.name")
            ->where([
                ['due_payments.subscriber_id', '=', Auth::user()->subscriber_id ]
            ])
            ->groupBy("due_payments.clientId", "clients.name")
            ->get();

        // Log::info($duePayment);


        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'due'=>$duePayment,
            ]);
        }


    }

    public function showDetails(Request $request, $clientId)
    {
        $client = Client::find($clientId);
        $datas = DB::table('deposits')
        ->select('deposits.*')
        ->where([
            ['deposits.client_id', $clientId],
            ['deposits.subscriber_id', Auth::user()->subscriber_id]
        ])
        ->distinct('deposits')
        ->get();

        $purchaseDeposit = DB::table("clients")
        ->join('payments', 'clients.id', '=', 'payments.clientId')
        ->where([
            ['payments.subscriber_id', '=', Auth::user()->subscriber_id],
            ['payments.clientId', '=', $clientId],
        ])
        ->get();

        foreach ($datas as $data){
            if($data->order_id== '0' || $data->order_id == '')
            {
                $data->totalPurchase = 0;
            }
            foreach ($purchaseDeposit as $purdep) {
                if ($data->created_at == $purdep->created_at) {
                    $data->totalPurchase = $purdep->total;
                }
            }
        }
        return view('report/due-report-details', ["datas" => $datas, 'client' => $client]);
    }

    // public function showSupplierDetails(Request $request, $supplierId, $head_code)
    // {
    //     $supplier_data = Supplier::find($supplierId);

    //     $duePayment = DB::table("suppliers")
    //     ->join('purchase_products', 'suppliers.id', '=', 'purchase_products.supplierId')
    //     ->join('transactions', 'suppliers.head_code', '=', 'transactions.head_code')
    //     // ->select('suppliers.*', DB::raw('(purchase_products.grandTotal + transactions.debit) AS totalPurchase'), 'purchase_products.poNumber')
    //     ->select('suppliers.*', 'transaction_date AS deposit_date', 'transactions.debit AS deposit', DB::raw('(purchase_products.grandTotal - transactions.debit) AS due'), DB::raw('(purchase_products.grandTotal + transactions.debit) AS totalPurchase'), 'purchase_products.poNumber')
    //     ->where([['purchase_products.supplierId', $supplierId], ['transactions.head_code', $head_code], ['suppliers.subscriber_id', Auth::user()->subscriber_id]])
    //     ->get();

    //     // $client = Client::find($clientId);
    //     // //old query
    //     // // $datas = DB::table("deposits")
    //     // //     // ->select(DB::raw("SUM(due) as due"), DB::raw("SUM(deposit) as deposit"), "deposit_date")
    //     // //     ->where([
    //     // //         ['client_id', $clientId],
    //     // //         ['subscriber_id', '=', Auth::user()->subscriber_id ]
    //     // //     ])
    //     // //     ->get();

    //     // // Log::info($datas);
    //     // // End old query

    //     // $datas = DB::table('deposits')
    //     // ->select('deposits.*', DB::raw('(deposits.deposit + deposits.due) AS totalPurchase'), 'orders.orderId')
    //     // ->join('orders', 'orders.clientId', '=', 'deposits.client_id')
    //     // ->where([['deposits.client_id', $clientId], ['orders.clientId', $clientId], ['deposits.subscriber_id', Auth::user()->subscriber_id]])
    //     //     ->get();

    //     return view('report/due-report-details', ["datas" => $duePayment, 'client' => $supplier_data]);
    // }
}
