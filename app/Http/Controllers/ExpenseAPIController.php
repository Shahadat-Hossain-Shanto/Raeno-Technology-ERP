<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Carbon\Carbon;;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExpenseAPIController extends Controller
{
    public function store(Request $request){

        Session::put('subscriberId', $request->subscriberId);
        // Log::info($request." Hello---------------------");

        $expense = new Expense;

        $users = User::where('contact_number', $request->submittedBy)
                    ->orWhere('email', $request->submittedBy)
                    ->get();

        foreach($users as $user){
            $userName =  $user->name;
        }

        // $depositDate                = strtotime($request->depositDate);
        // $deposit->deposit_date      = date('Y-m-d', $depositDate);

        $expense->expense_type   = $request->expenseType;
        $expense->amount         = doubleval($request->amount);
        $expense->note           = $request->note;
        $expense->image          = $request->image;
        $expense->submitted_by   = $userName;
        $expense->subscriber_id  = (int)$request->subscriberId;
        $expense->store_id       = (int)$request->storeId;

        $expenseDate             = strtotime($request->date);
        $expense->expense_date   = date('Y-m-d', $expenseDate);
        $expense->store_id       = (int)$request->storeId;

        $expense->save();

        // $depositId = $deposit->id;

        return response() -> json([
            'status'=>200,
            'message' => 'Expense added Successfully!',
            // 'type' => $request->type
        ]);
    }

    public function getExpenseId(){
        $expenseId = DB::table('expenses')->latest('id')->first();
        return $expenseId->id;
    }

    public function expenseList($subscriberId, $storeId){
        $expense = Expense::where([['subscriber_id', $subscriberId], ['store_id', $storeId]])->get();

        return response() -> json([
            'status'=>200,
            'expense' => $expense
        ]);
    }

    public function getExpenseMonth(Request $request, $subscriberId, $storeId, $month){

        $monthName = Carbon::createFromFormat('Y-m-d', $month)->month;

        $data = DB::table("expenses")
                    ->select(DB::raw("SUM(amount) as totalExpense"), "expense_type")
                    ->whereMonth('expense_date', $monthName)
                    ->where([['subscriber_id', $subscriberId], ['store_id', $storeId]])
                    ->groupBy("expense_type")
                    ->orderBy("totalExpense","desc")
                    ->get();

        $totalMonthExpense = 0;

        foreach($data as $d){
            $totalMonthExpense = $totalMonthExpense + $d->totalExpense;
        }

        $data1 = DB::table("salaries")
                    ->select(DB::raw("SUM(amount) as totalSalary"))
                    // ->select("amount")
                    ->whereMonth('salary_month', $monthName)
                    ->where([['subscriber_id', $subscriberId], ['store_id', $storeId]])
                    // ->groupBy("expense_type")
                    // ->orderBy("totalSalary","desc")
                    ->get();

        $totalMonthSalary = 0;

        foreach($data1 as $d1){
            $totalMonthSalary = $totalMonthSalary + $d1->totalSalary;
        }


        // foreach($data1 as $d1){
        //     // $totalMonthSalary = $totalMonthSalary + $d1->totalSalary;
        //     $x = [
        //         "salary" => $d1->totalSalary,
        //         "expense_type" => "Salary"
        //     ];
        // }

        return response() -> json([
            'status'=>200,
            'totalMonthExpense' => $totalMonthExpense + $totalMonthSalary,
            'totalMonthSalary' => $totalMonthSalary,
            'monthExpenses' => $data
        ]);

    }
}
