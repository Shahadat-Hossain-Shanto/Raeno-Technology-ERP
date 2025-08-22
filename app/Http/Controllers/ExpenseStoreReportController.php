<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\User;
use App\Models\Store;

use Illuminate\Support\Facades\Auth;
use Log;
use DB;
use Carbon\Carbon;

class ExpenseStoreReportController extends Controller
{
    public function index()
    {

        $expenseTypes = Expense::where('subscriber_id', Auth::user()->subscriber_id)
            ->select("expense_type")
            ->groupBy("expense_type")
            ->get();

        $employees = User::join('stores', 'users.store_id', 'stores.id')->select('users.*', 'stores.store_name')->where('users.subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('report/expense-store-report', ["expenseTypes" => $expenseTypes, "employees" => $employees, "stores" => $stores]);
    }

    public function showData(Request $request)
    {

        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();
        $monthName = Carbon::createFromFormat('Y-m-d', $date)->month;

        $totalExpenses =  DB::table("expenses")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(amount) as totalAmount"))
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['store_id', $request->store]
            ])
            ->get();
        foreach ($totalExpenses as $totalExpense) {
            $totalExpenseX = $totalExpense->totalAmount;
        }

        $totalSalaries =  DB::table("salaries")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(amount) as totalAmount"))
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['store_id', $request->store]
            ])
            ->get();

        if ($totalSalaries->isEmpty()) {
            $totalSalaryX = 0;
        } else {
            foreach ($totalSalaries as $totalSalary) {
                $totalSalaryX = $totalSalary->totalAmount;
            }
        }


        $overAllExpense = doubleval($totalExpenseX) + doubleval($totalSalaryX);

        $totalPurchases = DB::table("expenses")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(amount) as totalAmount"))
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['store_id', $request->store]
            ])
            ->where('expense_type', 'Purchase')
            ->get();

        foreach ($totalPurchases as $totalPurchase) {
            $totalPurchaseX = $totalPurchase->totalAmount;
        }

        $totalRents = DB::table("expenses")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(amount) as totalAmount"))
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['store_id', $request->store]
            ])
            ->where('expense_type', 'Rent')
            ->get();

        foreach ($totalRents as $totalRent) {
            $totalRentX = $totalRent->totalAmount;
        }

        $totalBills = DB::table("expenses")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(amount) as totalAmount"))
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['store_id', $request->store]
            ])
            ->where('expense_type', 'Bill')
            ->get();

        foreach ($totalBills as $totalBill) {
            $totalBillX = $totalBill->totalAmount;
        }

        $totalOthers = DB::table("expenses")
            // ->join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
            ->select(DB::raw("SUM(amount) as totalAmount"))
            ->where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['store_id', $request->store]
            ])
            ->where('expense_type', 'Others')
            ->get();

        foreach ($totalOthers as $totalOther) {
            $totalOtherX = $totalOther->totalAmount;
        }

        $data1 = DB::table("expenses")
            ->join('stores', 'expenses.store_id', 'stores.id')
            ->select('expenses.*', 'stores.store_name')
            ->where([
                ['expenses.subscriber_id', Auth::user()->subscriber_id],
                ['expenses.store_id', $request->store]
            ])
            // ->groupBy("expense_type")
            // ->take(5)
            ->get();

        //----------------------------------------------------------------------------------------------------------------------------------------------------------------------

        $from = date($request->startdate);
        $to = date($request->enddate);

        if ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $from)
                ->where([
                    ['expenses.subscriber_id', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                // ->take(5)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "Only From date",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1
            ]);
        } elseif ($request->filled('enddate') && $request->input('startdate') == NULL && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $to)
                ->where([
                    ['expenses.subscriber_id', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                // ->take(5)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "Only To date",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1
            ]);
        } elseif ($request->input('enddate') == NULL && $request->input('startdate') == NULL && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->where([
                    ['expenses.subscriber_id', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                // ->take(5)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "All",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1
            ]);
        } elseif ($request->filled('enddate') && $request->filled('startdate') && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                // ->whereBetween('expenses.expense_date', [$from, $to])
                ->whereBetween('expenses.expense_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['expenses.subscriber_id', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and to",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('enddate') && $request->filled('startdate') && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereBetween('expenses.expense_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and to and type",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('enddate') && $request->filled('startdate') && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') != 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereBetween('expenses.expense_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and to and type and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('enddate') && $request->filled('startdate') && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') != 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereBetween('expenses.expense_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and to and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $from)
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and type",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('enddate') && $request->input('startdate') == NULL && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') == 'option_select')) {

            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $to)
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "To and type",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') != 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $from)
                ->where([
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('enddate') && $request->input('startdate') == NULL && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') != 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $to)
                ->where([
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "To and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') != 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $from)
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "To and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->filled('enddate') && $request->input('startdate') == NULL && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') != 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->whereDate('expenses.expense_date', '=', $to)
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "To and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->input('enddate') == NULL && $request->input('startdate') == NULL && ($request->input('expensetype') == 'option_select') && ($request->input('byemployee') != 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->where([
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "To and employee",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->input('enddate') == NULL && $request->input('startdate') == NULL && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') == 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "Expense Type",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        } elseif ($request->input('enddate') == NULL && $request->input('startdate') == NULL && ($request->input('expensetype') != 'option_select') && ($request->input('byemployee') != 'option_select')) {
            $data = DB::table("expenses")
                ->join('stores', 'expenses.store_id', 'stores.id')
                ->select('expenses.*', 'stores.store_name')
                ->where([
                    ['expenses.expense_type', '=', $request->expensetype],
                    ['expenses.submitted_by', '=', $request->byemployee],
                    ['expenses.subscriber_id', '=', Auth::user()->subscriber_id],
                    ['expenses.store_id', $request->store]
                ])
                // ->groupBy("expense_type")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "Expense Type & sales by",
                'data' => $data,
                'totalSalary' => $totalSalaryX,
                'totalExpenseAmount' => $overAllExpense,
                'totalPurchase' => $totalPurchaseX,
                'totalRent' => $totalRentX,
                'totalBill' => $totalBillX,
                'totalOther' => $totalOtherX,
                'data1' => $data1

            ]);
        }
    }
}
