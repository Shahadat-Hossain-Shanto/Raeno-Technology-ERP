<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardAPIController extends Controller
{
    public function showDashboard($subscriberId){

        //SALE
        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();

        $todaySales = DB::table("orders")
        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
        ->whereDate('orders.orderDate', '=', $date)
            ->where('orders.subscriber_id', $subscriberId)
            ->groupBy("orders.orderDate")
            ->get();

        if ($todaySales->isEmpty()) {
            $todaySale = 0;
        } else {
            foreach ($todaySales as $todaySalex) {
                $todaySale = $todaySalex->grandTotal;
            }
        }


        $monthName = Carbon::createFromFormat('Y-m-d', $date)->month;

        // $monthNameX = Carbon::createFromFormat('Y-m-d', $date)->month;


        $monthSales = DB::table("orders")
        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
            ->whereMonth('orders.orderDate', $monthName)
            ->where('orders.subscriber_id', $subscriberId)
            ->groupBy("orders.orderDate")
            ->get();
            // log::alert($monthSales);
        $totalMonthSale = 0;
        foreach ($monthSales as $monthSalex) {
            $totalMonthSale = $totalMonthSale + $monthSalex->grandTotal;
        }

        // $totalSales = DB::table("ordered_products")
        //         // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
        //         ->select(DB::raw("SUM(grandTotal) as grandTotal"))
        //         ->where('subscriber_id', $subscriberId)
        //         ->get();

        $totalSales = DB::table("orders")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(grandTotal) as grandTotal"))
            ->where([
                ['orders.subscriber_id', $subscriberId],
                // ['orders.store_id', Auth::user()->store_id]
            ])
            ->get();

        $totalSale = 0;
        foreach ($totalSales as $totalSalex) {
            $totalSale = $totalSale + $totalSalex->grandTotal;
        }

        // Log::info($todaySale);
        // Log::info($totalMonthSale);
        // Log::info($totalSale);

        //DUE

        $clients = DB::table('clients')
            ->select('id')
            ->where('subscriber_id', $subscriberId)
            ->get();

            $todayDue = 0;
            foreach ($clients as $client) {
                    $todayDues = DB::table('deposits')
                        ->select('balance', 'due', 'deposit', 'client_id')
                        ->whereDate('deposit_date', '=', $date)
                        ->where([
                            ['subscriber_id',$subscriberId],
                            ['client_id', $client->id],
                            // ['balance', '<', 0]
                        ])
                    ->orderBy('created_at', 'desc')->get();
                    foreach ($todayDues as $due) {
                    if($due!=null&&$due->balance<0)
                    {
                        $todayDue = $todayDue + $due->due - $due->deposit;
                    }
                }
            }
            if ($todayDue < 0) {
                $todayDue = 0;
            }

        // $todayDues = DB::table('due_payments')
        // ->select(DB::raw("SUM(due_amount) as totalAmount"))
        // $todayDues = DB::table('deposits')
        //     ->select(DB::raw("SUM(balance) as totalAmount"))
        //     ->whereDate('deposit_date', '=', $date)
        //     ->where('subscriber_id', $subscriberId)
        //     ->get();

        // Log::info($todayDues);
        // foreach ($todayDues as $todayDuex) {
        //     $todayDue = $todayDuex->totalAmount;
        // }
        // if ($todayDue < 0) {
        //     $todayDue = -1 * $todayDue;
        // } else {
        //     $todayDue = 0;
        // }
        // $monthDues = DB::table('due_payments')
        //         ->select(DB::raw("SUM(due_amount) as totalAmount"))

        $monthDue = 0;
        foreach ($clients as $client) {
            $monthDues = DB::table('deposits')
            ->select('balance', 'due', 'deposit',
                'client_id'
            )
                ->whereMonth('created_at', '=', $monthName)
                ->where([
                    ['subscriber_id',$subscriberId],
                    ['client_id', $client->id],
                    // ['balance', '<', 0]
                ])
                ->orderBy('created_at', 'desc')->get();
                foreach ($monthDues as $due) {
                    if($due!=null&&$due->balance<0)
                    {
                        $monthDue = $monthDue + $due->due - $due->deposit;
                    }
            // if ($monthDues != null&&$monthDues->balance<0) {
            //     $monthDue = $monthDue + $monthDues->due;
            }
        }

        if ($monthDue < 0) {
            $monthDue = 0;
        }

        // $monthDues = DB::table('deposits')
        //     ->select(DB::raw("SUM(balance) as totalAmount"))
        //     ->whereMonth('created_at', '=', $monthName)
        //     ->where('subscriber_id',
        //         $subscriberId
        //     )
        //     ->get();
        // foreach ($monthDues as $monthDuex) {
        //     $monthDue = $monthDuex->totalAmount;
        // }
        // if ($monthDue < 0) {
        //     $monthDue = -1 * $monthDue;
        // } else {
        //     $monthDue = 0;
        // }
        // $totalDues = DB::table('due_payments')
        //         ->select(DB::raw("SUM(due_amount) as totalAmount"))
        // $totalDues = DB::table('deposits')
        //     ->select(DB::raw("SUM(balance) as totalAmount"))
        //     ->where('subscriber_id', $subscriberId)
        //     ->get();

        // foreach ($totalDues as $totalDuex) {
        //     $totalDue = $totalDuex->totalAmount;
        // }

        // if ($totalDue < 0) {
        //     $totalDue = -1 * $totalDue;
        // } else {
        //     $totalDue = 0;
        // }

        $totalDue = 0;
        foreach ($clients as $client) {
            $totalDues = DB::table('deposits')
            ->select(
                'balance',
                'client_id'
            )
            ->where([
                ['subscriber_id', $subscriberId],
                ['client_id', $client->id],
                // ['balance', '<', 0
                // ]
            ])
                ->orderBy('created_at', 'desc')->limit(1)->first();
            if ($totalDues != null&&$totalDues->balance<0) {
                $totalDue = $totalDue + $totalDues->balance;
            }
        }
        if ($totalDue < 0) {
            $totalDue = -1 * $totalDue;
        } else {
            $totalDue = 0;
        }
        // Log::info($todayDue);
        // Log::info($monthDue);
        // Log::info($totalDue);

        //EXPENSE
        $todayExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->whereDate('expense_date', '=', $date)
                ->where('subscriber_id', $subscriberId)
                ->get();
        $todayExpense =0;
        foreach($todayExpenses as $todayExpensex){
            $todayExpense = $todayExpense+$todayExpensex->totalExpense;
        }

        $monthExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->whereMonth('expense_date', '=', $monthName)
                ->where('subscriber_id', $subscriberId)
                ->get();
        $monthExpense =0;
        foreach($monthExpenses as $monthExpensex){
            $monthExpense = $monthExpense + $monthExpensex->totalExpense;
        }

        // $monthSalaries = DB::table('salaries')
        //         ->select(DB::raw("SUM(amount) as totalSalary"))
        //         ->whereMonth('salary_month', '=', $monthName)
        //         ->where('subscriber_id', $subscriberId)
        //         ->get();
        // $monthSalary = 0;
        // foreach($monthSalaries as $monthSalariex){
        //     $monthSalary = $monthSalary + $monthSalariex->totalSalary;
        //     // Log::info($monthSalary);
        // }

        // $totalMonthExpense = $monthExpense + $monthSalary;
        $totalMonthExpense = $monthExpense;

        $totalExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->where('subscriber_id', $subscriberId)
                ->get();
        $totalExpense =0;
        foreach($totalExpenses as $totalExpensex){
            $totalExpense = $totalExpense + $totalExpensex->totalExpense;
        }

        // $totalSalaries = DB::table('salaries')
        //         ->select(DB::raw("SUM(amount) as totalSalary"))
        //         ->where('subscriber_id', $subscriberId)
        //         ->get();
        // $totalSalary =0;
        // foreach($totalSalaries as $totalSalariex){
        //     $totalSalary = $totalSalary + $totalSalariex->totalSalary;
        // }

        // $totalExpenseSalary = $totalExpense+$totalSalary;
        $totalExpenseSalary = $totalExpense;

        // Log::info($todayExpense);
        // Log::info($monthExpense);
        // Log::info($totalExpense);

        //PURCHASE
        $todayPurchases = DB::table('purchase_products')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->whereDate('purchaseDate', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['status', 'received']
                ])
                ->get();

        // Log::info($todayPurchases);

        // if($todayPurchases->isEmpty()){
            $todayPurchase = 0;
        // }else{
            foreach($todayPurchases as $todayPurchasex){
                $todayPurchase = $todayPurchase+$todayPurchasex->grandTotal;
            }
        // }


        $monthPurchases = DB::table('purchase_products')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->whereMonth('purchaseDate', '=', $monthName)
            ->where([
                ['subscriber_id', $subscriberId],
                ['status', 'received']
            ])
                ->get();
        $monthPurchase=0;
        foreach($monthPurchases as $monthPurchasex){
            $monthPurchase = $monthPurchase+$monthPurchasex->grandTotal;
        }

        $totalPurchases = DB::table('purchase_products')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
            ->where([
                ['subscriber_id', $subscriberId],
                ['status', 'received']
            ])
                ->get();
        $totalPurchase =0;
        foreach($totalPurchases as $totalPurchasex){
            $totalPurchase = $totalPurchase + $totalPurchasex->grandTotal;
        }

        // Log::info($todayPurchase);
        // Log::info($monthPurchase);
        // Log::info($totalPurchase);

        return response()->json([
            'status'=>200,
            'todaySale'=>$todaySale,
            'totalMonthSale'=>$totalMonthSale,
            'totalSale'=>$totalSale,
            'todayDue'=>$todayDue,
            'totalMonthDue'=>$monthDue,
            'totalDue'=>$totalDue,
            'todayExpense'=>$todayExpense,
            'totalMonthExpense'=>$totalMonthExpense,
            'totalExpense'=>$totalExpenseSalary,
            'todayPurchase'=>$todayPurchase,
            'totalMonthPurchase'=>$monthPurchase,
            'totalPurchase'=>$totalPurchase,
            'message' => "Success"
        ]);

    }

    public function topProduct($subscriberId){
        $data1 =  DB::table("ordered_products")
        ->select(DB::raw("SUM(quantity) as qty"), "productName")
        ->where('subscriber_id', $subscriberId)
        ->groupBy("productName")
        ->orderBy("qty","desc")
        ->take(10)
        ->get();

        return $data1;

    }

    public function todaysSaleStock($subscriberId){
        $todayDate = Carbon::today()->toDateString();
        $data =  DB::table("ordered_products")
        ->select(DB::raw("SUM(quantity) as totalqty"))
        ->where([
            ['subscriber_id', $subscriberId],
            // ['created_at', $todayDate],
            ])
        ->whereBetween('created_at', [$todayDate . ' 00:00:00', $todayDate . ' 23:59:59'])
        ->get();

        return $data;
    }

    public function totalStock($storeId){
        $data =  DB::table("store_inventories")
        ->select(DB::raw("SUM(onHand) as totalqty"))
        ->where([
            ['store_id', $storeId]
            ])
        ->get();

        return $data;
    }

    public function outStock($subscriberId){
        $data =  DB::table("ordered_products")
        ->select(DB::raw("SUM(quantity) as totalqty"))
        ->where([
            ['subscriber_id', $subscriberId]
            ])
        ->get();

        return $data;
    }

    public function incomingStock($storeId){
        $data =  DB::table("store_inventories")
        ->select(DB::raw("SUM(productIncoming) as totalqty"))
        ->where([
            ['store_id', $storeId]
            ])
        ->get();

        return $data;
    }
}
