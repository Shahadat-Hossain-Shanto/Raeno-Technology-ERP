<?php

namespace App\Http\Services;

use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesService
{
    public function weeklySummary($subscriberId, $storeId){
        //DAY 0
        {
            $day0 = Carbon::now();
            $date = $day0->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale0 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale0 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense0 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense0 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due0 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due0 = $todayDuesx->totalDue;
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
            $deposit0 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit0 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit0 = $todayDepositX->totalDeposit;
                }
            }
        }

        //DAY 1
        {
            $day1 = $day0->subDay();
            $date = $day1->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale1 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale1 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense1 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense1 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due1 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due1 = $todayDuesx->totalDue;
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

            $deposit1 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit1 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit1 = $todayDepositX->totalDeposit;
                }
            }
        }

        //DAY 2
        {
            $day2 = $day1->subDay();
            $date = $day2->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale2 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale2 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense2 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense2 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due2 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due2 = $todayDuesx->totalDue;
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

            $deposit2 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit2 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit2 = $todayDepositX->totalDeposit;
                }
            }
        }

        //DAY 3
        {
            $day3 = $day2->subDay();
            $date = $day3->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale3 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale3 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense3 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense3 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due3 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due3 = $todayDuesx->totalDue;
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

            $deposit3 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit3 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit3 = $todayDepositX->totalDeposit;
                }
            }
        }

        //DAY 4
        {
            $day4 = $day3->subDay();
            $date = $day4->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale4 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale4 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense4 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense4 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due4 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due4 = $todayDuesx->totalDue;
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

            $deposit4 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit4 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit4 = $todayDepositX->totalDeposit;
                }
            }
        }

        //DAY 5
        {
            $day5 = $day4->subDay();
            $date = $day5->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale5 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale5 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense5 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense5 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due5 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due5 = $todayDuesx->totalDue;
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

            $deposit5 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit5 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit5 = $todayDepositX->totalDeposit;
                }
            }
        }

        //DAY 6
        {
            $day6 = $day5->subDay();
            $date = $day6->toDateString();
            //SALE
            $todaySales = DB::table("orders")
                        // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
                        ->select(DB::raw("SUM(orders.grandTotal) as grandTotal"))
                        ->whereDate('orders.orderDate', '=', $date)
                        ->where([
                            ['orders.subscriber_id', $subscriberId],
                            ['orders.store_id', $storeId]
                        ])
                        ->groupBy("orders.orderDate")
                        ->get();

            if($todaySales->isEmpty()){
                 $sale6 = 0;
            }else{
                foreach($todaySales as $todaySalex){
                    $sale6 = $todaySalex->grandTotal;
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

            if($todayExpenses->isEmpty() || $todayExpenses[0]->totalExpense==null){
                 $expense6 = 0;
            }else{
                foreach($todayExpenses as $todayExpensex){
                    $expense6 = $todayExpensex->totalExpense;
                }
            }

            //DUE
            $todayDues = DB::table('deposits')
                ->select(DB::raw("SUM(due) as totalDue"))
                ->whereDate('deposit_date', '=', $date)
                ->where([
                    ['subscriber_id', $subscriberId],
                    ['due', '>', 0]
                ])
                ->orderBy('created_at', 'desc')->get();

            if($todayDues->isEmpty() || $todayDues[0]->totalDue==null){
                $due6 = 0;
            }else{
                foreach($todayDues as $todayDuesx){
                    $due6 = $todayDuesx->totalDue;
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

            $deposit6 = 0;
            if($todayDeposits->isEmpty() || $todayDeposits[0]->totalDeposit==null){
                 $deposit6 = 0;
            }else{
                foreach($todayDeposits as $todayDepositX){
                    $deposit6 = $todayDepositX->totalDeposit;
                }
            }
        }

        $data=[
            [
                'day' => 0,
                'sale' => $sale0,
                'expense' => $expense0,
                'due' => $due0,
                'deposit' => $deposit0
            ],
            [
                'day' => 1,
                'sale' => $sale1,
                'expense' => $expense1,
                'due' => $due1,
                'deposit' => $deposit1
            ],
            [
                'day' => 2,
                'sale' => $sale2,
                'expense' => $expense2,
                'due' => $due2,
                'deposit' => $deposit2
            ],
            [
                'day' => 3,
                'sale' => $sale3,
                'expense' => $expense3,
                'due' => $due3,
                'deposit' => $deposit3
            ],
            [
                'day' => 4,
                'sale' => $sale4,
                'expense' => $expense4,
                'due' => $due4,
                'deposit' => $deposit4
            ],
            [
                'day' => 5,
                'sale' => $sale5,
                'expense' => $expense5,
                'due' => $due5,
                'deposit' => $deposit5
            ],
            [
                'day' => 6,
                'sale' => $sale6,
                'expense' => $expense6,
                'due' => $due6,
                'deposit' => $deposit6
            ]
        ];
        return response() -> json($data);
    }
}
