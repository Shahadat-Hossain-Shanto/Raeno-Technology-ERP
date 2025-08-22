<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardReportController extends Controller
{
    public function showDashboard(Request $request){

        //  $data = DB::table("ordered_products")
        //             ->select(DB::raw("SUM(grandTotal) as grandTotal"), "productName", date("created_at"))
        //             ->where('subscriber_id', Auth::user()->subscriber_id)
        //             ->groupBy("productName", "created_at")
        //             ->orderBy("grandTotal","asc")
        //             ->take(5)
        //             ->get();

        $data =  DB::table("orders")
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"), "orders.orderDate")
                ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("orders.orderDate")
                ->orderBy("orders.orderDate","desc")
                ->take(30)
                ->get();

        $data1 =  DB::table("ordered_products")
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("qty","desc")
                ->take(10)
                ->get();

        // $data = DB::table('orders')
        //         ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
        //         ->where('orders.subscriber_id', Auth::user()->subscriber_id)
        //         ->sum('ordered_products.grandTotal as grandTotal')
        //         ->max('grandTotal as maxGrandTotal')
        //         ->groupBy("orders.orderDate", "ordered_products.productName")
        //         ->orderBy("orders.orderDate","desc")
        //         ->take(10)
        //         ->get('maxGrandTotal', 'ordered_products.productName', 'orders.orderDate');

        $data2 = DB::table("expenses")
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(amount) as amount"), "expense_date")
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("expense_date")
                ->orderBy("expense_date","desc")
                ->take(10)
                ->get();

        $data3 =  DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.salesBy_id')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"), "users.name")
                ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("users.name")
                ->orderBy("orders.grandTotal","asc")
                ->take(10)
                ->get();

        //SALE
        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();

        $todaySales = DB::table("orders")
                    // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                    ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                    ->whereDate('orders.orderDate', '=', $date)
                    ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                    ->groupBy("orders.orderDate")
                    ->get();

        if($todaySales->isEmpty()){
             $todaySale = 0;
        }else{
            foreach($todaySales as $todaySalex){
                $todaySale = $todaySalex->grandTotal;
            }
        }


        $monthName = Carbon::createFromFormat('Y-m-d', $date)->month;

        // $monthNameX = Carbon::createFromFormat('Y-m-d', $date)->month;


        $monthSales = DB::table("orders")
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                ->whereMonth('orders.orderDate', $monthName)
                ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("orders.orderDate")
                ->get();
        $totalMonthSale = 0;
        foreach($monthSales as $monthSalex){
            $totalMonthSale = $totalMonthSale + $monthSalex->grandTotal;
        }

        // $totalSales = DB::table("ordered_products")
        //         // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
        //         ->select(DB::raw("SUM(grandTotal) as grandTotal"))
        //         ->where('subscriber_id', Auth::user()->subscriber_id)
        //         ->get();

        $totalSales = DB::table("orders")
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(grandTotal) as grandTotal "))
                ->where([
                    ['orders.subscriber_id', Auth::user()->subscriber_id],
                    // ['orders.store_id', Auth::user()->store_id]
                ])
                ->get();

        $totalSale = 0;
        foreach($totalSales as $totalSalex){
            $totalSale = $totalSale + $totalSalex->grandTotal;
        }

        // Log::info($todaySale);
        // Log::info($totalMonthSale);
        // Log::info($totalSale);

        //DUE
        // $todayDues = DB::table('due_payments')
        //         ->select(DB::raw("SUM(due_amount) as totalAmount"))
        //         ->whereDate('deposit_date', '=', $date)
        //         ->where('subscriber_id', Auth::user()->subscriber_id)
        //         ->get();

        // // Log::info($todayDues);
        // foreach($todayDues as $todayDuex){
        //     $todayDue = $todayDuex->totalAmount;
        // }

        // $monthDues = DB::table('due_payments')
        //         ->select(DB::raw("SUM(due_amount) as totalAmount"))
        //         ->whereMonth('created_at', '=', $monthName)
        //         ->where('subscriber_id', Auth::user()->subscriber_id)
        //         ->get();
        // foreach($monthDues as $monthDuex){
        //     $monthDue = $monthDuex->totalAmount;
        // }

        // $totalDues = DB::table('due_payments')
        //         ->select(DB::raw("SUM(due_amount) as totalAmount"))
        //         ->where('subscriber_id', Auth::user()->subscriber_id)
        //         ->get();

        // foreach($totalDues as $totalDuex){
        //     $totalDue = $totalDuex->totalAmount;
        // }

        $clients = DB::table('clients')
        ->select('id')
            ->where('subscriber_id', Auth::user()->subscriber_id)
            ->get();

        $todayDue = 0;
        foreach ($clients as $client) {
            $todayDues = DB::table('deposits')
                ->select('balance', 'client_id')
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['client_id', $client->id]
                ])
                ->orderBy('created_at', 'desc')->limit(1)->first();
            if ($todayDues != null && $todayDues->balance <0) {
                $todayDue = $todayDue + $todayDues->balance;
            }
        }

        if ($todayDue < 0) {
            $todayDue = -1 * $todayDue;
        } else {
            $todayDue = 0;
        }

        $monthDue = 0;
        foreach ($clients as $client) {
            $monthDues = DB::table('deposits')
                ->select(
                    'balance',
                    'client_id'
                )
                ->whereMonth('created_at', '=', $monthName)
                ->where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['client_id', $client->id]
                ])
                ->orderBy('created_at', 'desc')->limit(1)->first();
            if ($monthDues != null && $monthDues->balance<0) {
                $monthDue = $monthDue + $monthDues->balance;
            }
        }

        if ($monthDue < 0) {
            $monthDue = -1 * $monthDue;
        } else {
            $monthDue = 0;
        }

        $totalDue = 0;
        foreach ($clients as $client) {
            $totalDues = DB::table('deposits')
            ->select(
                'balance',
                'client_id'
            )
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['client_id', $client->id]
            ])
            ->orderBy('created_at', 'desc')->limit(1)->first();
            // Log::info(json_encode($totalDues));

            if ($totalDues != null && $totalDues->balance<0) {
                $totalDue = $totalDue + $totalDues->balance;
            }
        }
        if ($totalDue < 0
        ) {
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
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        $todayExpense = 0;
        foreach ($todayExpenses as $todayExpensex) {
            $todayExpense = $todayExpense + $todayExpensex->totalExpense;
        }

        $monthExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->whereMonth('expense_date', '=', $monthName)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        $monthExpense = 0;
        foreach ($monthExpenses as $monthExpensex) {
            $monthExpense = $monthExpense + $monthExpensex->totalExpense;
        }

        $monthSalaries = DB::table('salaries')
                ->select(DB::raw("SUM(amount) as totalSalary"))
                ->whereMonth('salary_month', '=', $monthName)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        $monthSalary=0;
        foreach($monthSalaries as $monthSalariex){
            $monthSalary = $monthSalary+$monthSalariex->totalSalary;
            // Log::info($monthSalary);
        }

        $totalMonthExpense = $monthExpense + $monthSalary;

        $totalExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        $totalExpense = 0;
        foreach($totalExpenses as $totalExpensex){
            $totalExpense = $totalExpensex->totalExpense;
        }

        $totalSalaries = DB::table('salaries')
                ->select(DB::raw("SUM(amount) as totalSalary"))
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->get();
        $totalSalary = 0;
        foreach($totalSalaries as $totalSalariex){
            $totalSalary = $totalSalary+$totalSalariex->totalSalary;
        }

        $totalExpenseSalary = $totalExpense+$totalSalary;

        // Log::info($todayExpense);
        // Log::info($monthExpense);
        // Log::info($totalExpense);

        //PURCHASE
        $todayPurchases = DB::table('purchase_products')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->whereDate('purchaseDate', '=', $date)
                ->where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['status', 'received']
                ])
                ->get();
        $todayPurchase = 0;
        foreach($todayPurchases as $todayPurchasex){
            $todayPurchase = $todayPurchase + $todayPurchasex->grandTotal;
        }

        $monthPurchases = DB::table('purchase_products')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->whereMonth('purchaseDate', '=', $monthName)
                ->where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['status', 'received']
                ])
                ->get();
        $monthPurchase = 0;
        foreach ($monthPurchases as $monthPurchasex) {
            $monthPurchase = $monthPurchase + $monthPurchasex->grandTotal;
        }

        $totalPurchases = DB::table('purchase_products')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['status', 'received']
                ])
                ->get();
        $totalPurchase=0;
        foreach($totalPurchases as $totalPurchasex){
            $totalPurchase = $totalPurchase+$totalPurchasex->grandTotal;
        }

        // Log::info($todayPurchase);
        // Log::info($monthPurchase);
        // Log::info($totalPurchase);

        //Yearly purchase-------------------------------------------------------------------------------------------

        $dataY = [];
        // Circle trough all 12 months
        for ($monthY = 1; $monthY <= 12; $monthY++) {

            // $date = Carbon::create(date('Y'), $monthY);
            // $date_end = $date->copy()->endOfMonth();
            // $monthlySale = PurchaseProduct::where('product_id', $produk->id)
            //     ->where('created_at', '>=', $date)
            //     ->where('created_at', '<=', $date_end)
            //     ->count();
            // $data[$monthY] = $monthlySale;

            $yearName = Carbon::createFromFormat('Y-m-d', $date)->year;

            $monthlyPurchase = DB::table('purchase_products')
            ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->whereYear('purchaseDate', '=', $yearName)
                ->whereMonth('purchaseDate', '=', $monthY)
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['status', 'received']
            ])
                ->get();

            foreach($monthlyPurchase as $monthlyPurchasex){
                $dataY[$monthY] = $monthlyPurchasex->grandTotal;
            }

        }

        $jsonDataY = json_encode($dataY);
        $jsonDataYArr[] = $jsonDataY;

        // Log::info($jsonDataY);

        //Yearly Sale-------------------------------------------------------------------------------------------
        $dataX = [];
        // Circle trough all 12 months
        for ($monthX = 1; $monthX <= 12; $monthX++) {

            $yearName = Carbon::createFromFormat('Y-m-d', $date)->year;
            $monthlySales =  DB::table('orders')
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                ->whereYear('orders.orderDate', '=', $yearName)
                ->whereMonth('orders.orderDate', '=', $monthX)
                ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->get();

            foreach($monthlySales as $monthlySalex){
                $dataX[$monthX] = $monthlySalex->grandTotal;
            }

        }

        $jsonDataX = json_encode($dataX);
        $jsonDataXArr[] = $jsonDataX;

        // Log::info($jsonDataX);
        // Log::info($jsonDataXArr);
        // Log::info($jsonDataYArr);

        return response()->json([
            'status'=>200,
            'data'=>$data,
            'data1'=>$data1,
            'data2'=>$data2,
            'data3'=>$data3,
            'todaySale'=>$todaySale,
            'totalMonthSale'=>$totalMonthSale,
            'totalSale'=>$totalSale,
            'todayDue'=>$todayDue,
            'totalMonthDue'=>$monthDue,
            'totalDue'=>$totalDue,
            'todayExpense'=>$todayExpense,
            // 'totalMonthExpense'=>$monthExpense,
            'totalMonthExpense'=>$totalMonthExpense,

            // 'totalExpense'=>$totalExpense,
            'totalExpense'=>$totalExpenseSalary,

            'todayPurchase'=>$todayPurchase,
            'totalMonthPurchase'=>$monthPurchase,
            'totalPurchase'=>$totalPurchase,
            'yearlyPurchase' => $jsonDataYArr,
            'yearlySale' => $jsonDataXArr,
            'message' => "Success"
        ]);

    }

    public function storeSeller(Request $request, $storeId){

        if($storeId == 'all_store'){
            $data3 =  DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.salesBy_id')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"), "users.name")
                ->where([
                    ['orders.subscriber_id', Auth::user()->subscriber_id]
                ])
                ->groupBy("users.name")
                ->orderBy("orders.grandTotal","asc")
                ->take(10)
                ->get();
        }else{
            $data3 =  DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.salesBy_id')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"), "users.name")
                ->where([
                    ['orders.subscriber_id', Auth::user()->subscriber_id],
                    ['orders.store_id', $storeId]
                ])
                ->groupBy("users.name")
                ->orderBy("orders.grandTotal","asc")
                ->take(10)
                ->get();
        }



        return response()->json([
            'status'=>200,
            'data3'=>$data3,
            'message' => "Success"
        ]);
    }

    public function storeSale(Request $request){

        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();
        $monthName = Carbon::createFromFormat('Y-m-d', $date)->month;

        $data =  DB::table('orders')
                ->join('stores', 'stores.id', '=', 'orders.store_id')
                ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"), "stores.store_name")
                ->whereMonth('orders.orderDate', $monthName)
                ->where([
                    ['orders.subscriber_id', Auth::user()->subscriber_id]
                ])
                ->groupBy("stores.store_name")
                ->orderBy("orders.grandTotal","asc")
                ->get();

        return response()->json([
            'status'=>200,
            'data'=>$data,
            'message' => 'Success'
        ]);
    }
}
