<?php

namespace App\Http\Controllers;

use DB;

use Log;
use Carbon\Carbon;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositReportController extends Controller
{
    public function index(){
        return view('report/deposit-report');
    }

    public function onLoad(Request $request){
        $totalSales = DB::table("orders")
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        foreach($totalSales as $totalSaleX){
            $totalSale = $totalSaleX->grandTotal;
        }

        $totalDeposits = DB::table("deposits")
                ->select(DB::raw("SUM(deposit) as deposit"))
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        foreach($totalDeposits as $totalDepositX){
            $totalDeposit = $totalDepositX->deposit;
        }

        // $totalDues = DB::table("due_payments")
        //         ->select(DB::raw("SUM(due_amount) as due_amount"))
        //         ->where('subscriber_id', Auth::user()->subscriber_id)
        //         ->get();
        // foreach($totalDues as $totalDueX){
        //     $totalDue = $totalDueX->due_amount;
        // }
        $clients = DB::table('clients')
        ->select('id')
            ->where('subscriber_id', Auth::user()->subscriber_id)
            ->get();
        $totalDue = 0;
        foreach ($clients as $client) {
            $totalDues = DB::table('deposits')
            ->select(
                'balance',
                'client_id'
            )
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['client_id', $client->id],
                [
                    'balance', '<', 0
                ]
            ])
            ->orderBy('created_at', 'desc')->limit(1)->first();
            // Log::info(json_encode($totalDues));

            if ($totalDues != null) {
                $totalDue = $totalDue + $totalDues->balance;
            }
        }
        if ($totalDue < 0
        ) {
            $totalDue = -1 * $totalDue;
        } else {
            $totalDue = 0;
        }

        $data = DB::table("deposits")
                ->select(DB::raw("SUM(due) as due"), DB::raw("SUM(deposit) as deposit"), "deposit_date",)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("deposit_date")
                // ->take(5)
                ->get();

        return response() -> json([
            'status'=>200,
            'message' => 'Success',
            'totalSale' => $totalSale,
            'totalDeposit' => $totalDeposit,
            'totalDue' => $totalDue,
            'data' => $data

        ]);
    }

    public function showData(Request $request){
        $from = date($request->startdate);
        $to = date($request->enddate);

        if($request->filled('startdate') && $request->input('enddate') == NULL ){
            $data = DB::table("deposits")
                ->select(DB::raw("SUM(due) as due"), DB::raw("SUM(deposit) as deposit"), "deposit_date",)
                ->whereDate('deposit_date', '=', $from)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("deposit_date")
                // ->take(5)
                ->get();

                return response()->json([
                    'status'=>200,
                    'message'=>"Only From date",
                    'data' => $data,
                ]);
        }elseif($request->filled('enddate') && $request->input('startdate') == NULL ){
            $data = DB::table("deposits")
                ->select(DB::raw("SUM(due) as due"), DB::raw("SUM(deposit) as deposit"), "deposit_date",)
                ->whereDate('deposit_date', '=', $to)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("deposit_date")
                // ->take(5)
                ->get();

                return response()->json([
                    'status'=>200,
                    'message'=>"Only To date",
                    'data' => $data,
                ]);
        }elseif($request->filled('enddate') && $request->filled('startdate')){
            $data = DB::table("deposits")
                ->select(DB::raw("SUM(due) as due"), DB::raw("SUM(deposit) as deposit"), "deposit_date",)
                 ->whereBetween('deposit_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("deposit_date")
                // ->take(5)
                ->get();

                return response()->json([
                    'status'=>200,
                    'message'=>"Only FROM AND TO date",
                    'data' => $data,
                ]);
        }elseif($request->input('enddate') == NULL && $request->input('startdate') == NULL ){
            $data = DB::table("deposits")
                ->select(DB::raw("SUM(due) as due"), DB::raw("SUM(deposit) as deposit"), "deposit_date",)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("deposit_date")
                // ->take(5)
                ->get();

            return response()->json([
                'status'=>200,
                'message'=>"Only FROM AND TO date",
                'data' => $data,
            ]);

        }

    }

    public function depositReportDetails(Request $request, $depositDate){
        $deposits = DB::table('deposits')
        ->leftjoin('clients', 'deposits.client_id', 'clients.id')
        ->leftjoin('suppliers', 'deposits.supplier_id', 'suppliers.id')
        ->select('deposits.*', 'suppliers.name as supplierName', 'clients.name as clientName')
        ->where([
            ['deposits.deposit_date', $depositDate],
            ['deposits.subscriber_id', Auth::user()->subscriber_id]
        ])
        // ->get(['deposits.*', 'suppliers.name as supplierName', 'clients.name as clientName']);
        ->get();
        return response()->json([
            'status'=>200,
            'message'=>"Success",
            'data' => $deposits,
        ]);
    }
}
