<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class EmployeeReportController extends Controller
{
    public function index()
    {

        $salesBy = User::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('report/employee-report', ['salesBy' => $salesBy]);
    }

    public function onLoad(Request $request)
    {
        $datas = DB::table("orders")
            ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(specialDiscount) as sd"),"salesBy_id")
            ->where('subscriber_id', '=', Auth::user()->subscriber_id)
            ->groupBy("salesBy_id")
            // ->orderBy("qty","desc")
            // ->take(5)
            ->get();

        foreach ($datas as $data) {
            // $x = $data->salesBy_id;

            $users = User::where('id', $data->salesBy_id)
                ->orWhere('email', $data->salesBy_id)
                ->orWhere('contact_number', $data->salesBy_id)
                ->get("name");

            $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();

            foreach ($users as $user) {

                // $avg = $data->t / $orders;

                $arr = [
                    'userName' => $user->name,
                    'grossSales' => $data->t,
                    'discounts' => $data->td + $data->sd,
                    'netSales' => $data->t - $data->td - $data->sd,
                    'averageSale' => round($data->t / $orders, 2),
                    'orderCount' => $orders
                ];

                $sales_arr[] = $arr;
            }
        }

        return response()->json([
            'status' => 200,
            'data' => $sales_arr,
            'message' => 'All select',
        ]);
    }

    public function showReport(Request $request)
    {

        if (($request->input('employee') == 'option_select') && ($request->input('enddate') == NULL) && ($request->input('startdate') == NULL)) {

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"), "salesBy_id")
                ->where('subscriber_id', '=', Auth::user()->subscriber_id)
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();

                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            return response()->json([
                'status' => 200,
                'data' => $sales_arr,
                'message' => 'All select',
            ]);
        } elseif ($request->filled('employee') && ($request->input('startdate') == NULL)  && ($request->input('enddate') == NULL) && ($request->input('employee') != 'option_select')) {

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"), "salesBy_id")
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();

                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            return response()->json([
                'status' => 200,
                'data' => $sales_arr,
                'message' => 'Emp!',
            ]);
        } elseif ($request->filled('startdate') && $request->filled('enddate') && ($request->input('employee') == 'option_select')) {

            $from = date($request->startdate);
            $to = date($request->enddate);

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"), "salesBy_id")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("salesBy_id")
                ->get();

            log::info($datas);

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                // $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();
                $orders = Order::where([
                    ['salesBy_id',  $data->salesBy_id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->count();

                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            if (isset($sales_arr)) {
                return response()->json([
                    'status' => 200,
                    'data' => $sales_arr,
                    'message' => 'Between From and to',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No Data available',
                ]);
            }
        } elseif ($request->filled('startdate') && ($request->input('employee') == 'option_select') && ($request->input('enddate') == NULL)) {

            $from = date($request->startdate);

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"), "salesBy_id")
                ->whereDate('created_at', '=', $from)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                // $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();
                $orders = Order::where([
                    ['salesBy_id',  $data->salesBy_id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                    ->whereDate('created_at', '=', $from)
                    ->count();

                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            if (isset($sales_arr)) {
                return response()->json([
                    'status' => 200,
                    'data' => $sales_arr,
                    'message' => 'From',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No Data available',
                ]);
            }
        } elseif ($request->filled('enddate') && ($request->input('employee') == 'option_select') && ($request->input('startdate') == NULL)) {

            $to = date($request->enddate);

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"),"salesBy_id")
                ->whereDate('created_at', '=', $to)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                // $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();
                $orders = Order::where([
                    ['salesBy_id',  $data->salesBy_id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                    ->whereDate('created_at', '=', $to)
                    ->count();

                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            if (isset($sales_arr)) {
                return response()->json([
                    'status' => 200,
                    'data' => $sales_arr,
                    'message' => 'To',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No Data available',
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('startdate') && ($request->input('enddate') == NULL)) {

            $from = date($request->startdate);

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"), "salesBy_id")
                ->whereDate('created_at', '=', $from)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                // $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();
                $orders = Order::where([
                    ['salesBy_id',  $data->salesBy_id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                    ->whereDate('created_at', '=', $from)
                    ->count();


                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            if (isset($sales_arr)) {
                return response()->json([
                    'status' => 200,
                    'data' => $sales_arr,
                    'message' => 'From and emp!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No Data available',
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('enddate') && ($request->input('startdate') == NULL)) {

            $to = date($request->enddate);

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"), "salesBy_id")
                ->whereDate('created_at', '=', $to)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                // $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();
                $orders = Order::where([
                    ['salesBy_id',  $data->salesBy_id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                    ->whereDate('created_at', '=', $to)
                    ->count();

                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }

            if (isset($sales_arr)) {
                return response()->json([
                    'status' => 200,
                    'data' => $sales_arr,
                    'message' => 'To and emp!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No Data available',
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('enddate') && $request->filled('startdate')) {

            $from = date($request->startdate);
            $to = date($request->enddate);

            $datas = DB::table("orders")
                ->select(DB::raw("SUM(total) as t"), DB::raw("SUM(totalDiscount) as td"),  DB::raw("SUM(specialDiscount) as sd"),"salesBy_id")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("salesBy_id")
                // ->orderBy("qty","desc")
                // ->take(5)
                ->get();

            foreach ($datas as $data) {
                // $x = $data->salesBy_id;

                $users = User::where('id', $data->salesBy_id)
                    ->orWhere('email', $data->salesBy_id)
                    ->orWhere('contact_number', $data->salesBy_id)
                    ->get("name");

                // $orders = Order::where('salesBy_id',  $data->salesBy_id)->count();
                $orders = Order::where([
                    ['salesBy_id',  $data->salesBy_id],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->count();


                foreach ($users as $user) {

                    // $avg = $data->t / $orders;

                    $arr = [
                        'userName' => $user->name,
                        'grossSales' => $data->t,
                        'discounts' => $data->td + $data->sd,
                        'netSales' => $data->t - $data->td - $data->sd,
                        'averageSale' => round($data->t / $orders, 2),
                        'orderCount' => $orders
                    ];

                    $sales_arr[] = $arr;
                }
            }
            if (isset($sales_arr)) {
                return response()->json([
                    'status' => 200,
                    'data' => $sales_arr,
                    'message' => 'Between From & To and Emp!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No Data available',
                ]);
            }
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Something Wrong!',
            ]);
        }
    }
}
