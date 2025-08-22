<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Services\SalesService;

class SummaryAPIController extends Controller
{
    public function data(Request $request, $subscriberId, $storeId){
        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();

        $todaySales = DB::table("orders")
                    ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                    ->select(DB::raw("SUM(ordered_products.grandTotal) as grandTotal"))
                    ->whereDate('orders.orderDate', '=', $date)
                    ->where([
                        ['orders.subscriber_id', $subscriberId],
                        ['orders.store_id', $storeId]
                    ])
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
        $yearName = Carbon::createFromFormat('Y-m-d', $date)->year;

        $monthSales = DB::table("orders")
                ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(ordered_products.grandTotal) as grandTotal"))
                ->whereMonth('orders.orderDate', $monthName)
                ->where([
                    ['orders.subscriber_id', $subscriberId],
                    ['orders.store_id', $storeId]
                ])
                ->groupBy("orders.orderDate")
                ->get();

        $totalMonthSale = 0;
        if($monthSales->isEmpty()){
            $totalMonthSale = 0;
        }else{
            foreach($monthSales as $monthSalex){
                $totalMonthSale =  $totalMonthSale + $monthSalex->grandTotal;
            }
        }

        $yearSales = DB::table("orders")
                ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(ordered_products.grandTotal) as grandTotal"))
                ->whereYear('orders.orderDate', $yearName)
                ->where([
                    ['orders.subscriber_id', $subscriberId],
                    ['orders.store_id', $storeId]
                ])
                ->groupBy("orders.orderDate")
                ->get();

        $totalYearSale = 0;
        if($yearSales->isEmpty()){
            $totalYearSale = 0;
        }else{
            foreach($yearSales as $yearSaleX){
                $totalYearSale =  $totalYearSale + $yearSaleX->grandTotal;
            }
        }


        $totalSales = DB::table("orders")
                // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                ->select(DB::raw("SUM(grandTotal) as grandTotal"))
                ->where([
                    ['orders.subscriber_id', $subscriberId],
                    ['orders.store_id', $storeId]
                ])
                ->get();

        if($totalSales->isEmpty()){
            $totalSale = 0;
        }else{
            foreach($totalSales as $totalSalex){
                $totalSale = $totalSalex->grandTotal;
            }
        }


        //EXPENSE
        $todayExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->whereDate('expense_date', '=', $date)
                ->where([
                    ['expenses.subscriber_id', $subscriberId],
                    ['expenses.store_id', $storeId]
                ])
                ->get();

        if($todayExpenses->isEmpty()){
             $todayExpense = 0;
        }else{
            foreach($todayExpenses as $todayExpensex){
                $todayExpense = $todayExpensex->totalExpense;
            }
        }

        $monthExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->whereMonth('expense_date', '=', $monthName)
                ->where([
                    ['expenses.subscriber_id', $subscriberId],
                    ['expenses.store_id', $storeId]
                ])
                ->get();

        $monthExpense = 0;
        if($monthExpenses->isEmpty()){
             $monthExpense = 0;
        }else{
            foreach($monthExpenses as $monthExpensex){
                $monthExpense = $monthExpense + $monthExpensex->totalExpense;
            }
        }

        $yearExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->whereYear('expense_date', '=', $yearName)
                ->where([
                    ['expenses.subscriber_id', $subscriberId],
                    ['expenses.store_id', $storeId]
                ])
                ->get();

        $yearExpense = 0;
        if($yearExpenses->isEmpty()){
             $yearExpense = 0;
        }else{
            foreach($yearExpenses as $yearExpensex){
                $yearExpense = $yearExpense + $yearExpensex->totalExpense;
            }
        }




        $totalExpenses = DB::table('expenses')
                ->select(DB::raw("SUM(amount) as totalExpense"))
                ->where([
                    ['expenses.subscriber_id', $subscriberId],
                    ['expenses.store_id', $storeId]
                ])
                ->get();

        if($totalExpenses->isEmpty()){
             $totalExpense = 0;
        }else{
            foreach($totalExpenses as $totalExpensex){
                $totalExpense = $totalExpensex->totalExpense;
            }
        }


        // Log::info($todayExpense);
        //DUE
        $todayDues = DB::table('due_payments')
                ->select(DB::raw("SUM(due_amount) as totalAmount"))
                ->whereDate('created_at', '=', $date)
                ->where([
                    ['due_payments.subscriber_id', $subscriberId],
                    ['due_payments.store_id', $storeId]
                ])
                ->get();

        if($todayDues->isEmpty()){
             $todayDue = 0;
        }else{
            foreach($todayDues as $todayDuex){
                $todayDue = $todayDuex->totalAmount;
            }
        }


        $monthDues = DB::table('due_payments')
                ->select(DB::raw("SUM(due_amount) as totalAmount"))
                ->whereMonth('created_at', '=', $monthName)
                ->where([
                    ['due_payments.subscriber_id', $subscriberId],
                    ['due_payments.store_id', $storeId]
                ])
                ->get();

        $monthDue = 0;
        if($monthDues->isEmpty()){
             $monthDue = 0;
        }else{
            foreach($monthDues as $monthDuex){
                $monthDue = $monthDue + $monthDuex->totalAmount;
            }
        }

        $yearDues = DB::table('due_payments')
                ->select(DB::raw("SUM(due_amount) as totalAmount"))
                ->whereYear('created_at', '=', $yearName)
                ->where([
                    ['due_payments.subscriber_id', $subscriberId],
                    ['due_payments.store_id', $storeId]
                ])
                ->get();

        $yearDue = 0;
        if($yearDues->isEmpty()){
             $yearDue = 0;
        }else{
            foreach($yearDues as $yearDuex){
                $yearDue = $yearDue + $yearDuex->totalAmount;
            }
        }


        $totalDues = DB::table('due_payments')
                ->select(DB::raw("SUM(due_amount) as totalAmount"))
                ->where([
                    ['due_payments.subscriber_id', $subscriberId],
                    ['due_payments.store_id', $storeId]
                ])
                ->get();

        if($totalDues->isEmpty()){
             $totalDue = 0;
        }else{
            foreach($totalDues as $totalDuex){
                $totalDue = $totalDuex->totalAmount;
            }
        }

        //Deposit
        $todayDeposits = DB::table('deposits')
                ->select(DB::raw("SUM(deposit) as totalDeposit"))
                ->whereDate('deposits.deposit_date', '=', $date)
                ->where([
                    ['deposits.subscriber_id', $subscriberId],
                    ['deposits.store_id', $storeId]
                ])
                ->get();

        $todayDeposit = 0;
        if($todayDeposits->isEmpty()){
             $todayDeposit = 0;
        }else{
            foreach($todayDeposits as $todayDepositX){
                $todayDeposit = $todayDepositX->totalDeposit;
            }
        }

        $monthDeposits = DB::table('deposits')
                ->select(DB::raw("SUM(deposit) as totalDeposit"))
                ->whereMonth('deposits.deposit_date', '=', $monthName)
                ->where([
                    ['deposits.subscriber_id', $subscriberId],
                    ['deposits.store_id', $storeId]
                ])
                ->get();

        $monthDeposit = 0;
        if($monthDeposits->isEmpty()){
             $monthDeposit = 0;
        }else{
            foreach($monthDeposits as $monthDepositX){
                $monthDeposit = $monthDeposit + $monthDepositX->totalDeposit;
            }
        }

        $yearDeposits = DB::table('deposits')
                ->select(DB::raw("SUM(deposit) as totalDeposit"))
                ->whereYear('deposits.deposit_date', '=', $yearName)
                ->where([
                    ['deposits.subscriber_id', $subscriberId],
                    ['deposits.store_id', $storeId]
                ])
                ->get();

        $yearDeposit = 0;
        if($yearDeposits->isEmpty()){
             $yearDeposit = 0;
        }else{
            foreach($yearDeposits as $yearDepositX){
                $yearDeposit = $yearDeposit + $yearDepositX->totalDeposit;
            }
        }


        $totalDeposits = DB::table('deposits')
                ->select(DB::raw("SUM(deposit) as totalDeposit"))
                ->where([
                    ['deposits.subscriber_id', $subscriberId],
                    ['deposits.store_id', $storeId]
                ])
                ->get();

        if($totalDeposits->isEmpty()){
             $totalDeposit = 0;
        }else{
            foreach($totalDeposits as $totalDepositX){
                $totalDeposit = $totalDepositX->totalDeposit;
            }
        }


        return response() -> json([
            'status' => 200,
            'message'=> 'Success',
            'todaySales'=>$todaySale,
            'monthSales'=>$totalMonthSale,
            'yearSales'=>$totalYearSale,
            'totalSales'=>$totalSale,
            'todayExpense'=>$todayExpense,
            'totalMonthExpense'=>$monthExpense,
            'totalYearExpense'=>$yearExpense,
            'totalExpense'=>$totalExpense,
            'todayDue'=>$todayDue,
            'totalMonthDue'=>$monthDue,
            'totalYearDue'=>$yearDue,
            'totalDue'=>$totalDue,
            'todayDeposit'=>$todayDeposit,
            'totalMonthDeposit'=>$monthDeposit,
            'totalYearDeposit'=>$yearDeposit,
            'totalDeposit'=>$totalDeposit,

        ]);
    }

    public function WeeklyData($subscriberId, $storeId){
        return (new SalesService)->weeklySummary($subscriberId, $storeId);
    }
}
