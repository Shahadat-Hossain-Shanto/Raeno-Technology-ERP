<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;

//use DB;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class SummaryReportController extends Controller
{
    public function index()
    {
        $salesBy = User::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('report/summary-report', compact('salesBy'));
    }

    public function showReport(Request $request)
    {

        if ($request->filled('startdate') && $request->filled('enddate') && ($request->input('employee') == 'option_select')) {

            $from = date($request->startdate);
            $to = date($request->enddate);

            $data = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {

                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('startdate') && ($request->input('employee') == 'option_select') && ($request->input('enddate') == NULL)) {
            $from = date($request->startdate);

            $data = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {

                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('enddate') && ($request->input('employee') == 'option_select') && ($request->input('startdate') == NULL)) {

            $to = date($request->enddate);

            $data = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

            $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('employee') && ($request->input('startdate') == NULL)  && ($request->input('enddate') == NULL && ($request->input('employee') != 'option_select'))) {
            $data = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

                $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('startdate') && ($request->input('enddate') == NULL)) {
            $from = date($request->startdate);

            $data = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

                $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->whereDate('created_at', [$from])
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {

                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('enddate') && ($request->input('startdate') == NULL)) {
            $to = date($request->enddate);

            $data = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

                $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->whereDate('created_at', [$to])
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('enddate') && $request->filled('startdate')) {
            $from = date($request->startdate);
            $to = date($request->enddate);

            $data = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

                $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {

                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        } elseif (($request->input('employee') == 'option_select') && ($request->input('enddate') == NULL) && ($request->input('startdate') == NULL)) {
            $data = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(grandTotal) AS gt'), 'productName')
                ->orderBy('gt', 'desc')
                ->groupBy('productName')
                ->get();

            $data1 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(quantity) AS qty'), 'productName')
                ->orderBy('qty', 'desc')
                ->groupBy('productName')
                ->get();

            $data2 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalDiscount+specialDiscount) AS td'), 'productName')
                ->orderBy('td', 'desc')
                ->groupBy('productName')
                ->get();

            $data3 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalPrice) AS tp'), 'productName')
                ->orderBy('tp', 'desc')
                ->groupBy('productName')
                ->get();

                $data4 = DB::table('ordered_products')
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->select(DB::raw('SUM(totalTax) AS tt'), 'productName')
                ->orderBy('tt', 'desc')
                ->groupBy('productName')
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data'  => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'data4' => $data4,
                    'message' => "From and to"
                ]);
            }
        }
    }
}
