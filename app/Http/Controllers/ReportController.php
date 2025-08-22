<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {

        $salesBy = User::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('report/report', ['salesBy' => $salesBy]);
    }

    public function onLoad(Request $request)
    {
        $data = DB::table("ordered_products")
            ->select(DB::raw("SUM(quantity) as qty"), "productName")
            ->where('subscriber_id', '=', Auth::user()->subscriber_id)
            ->groupBy("productName")
            ->orderBy("qty", "desc")
            ->take(5)
            ->get();

        $data1 = DB::table("ordered_products")
            ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
            ->where('subscriber_id', '=', Auth::user()->subscriber_id)
            ->groupBy("productName")
            ->orderBy("gt", "desc")
            ->get();

        $data2 = DB::table("ordered_products")
            ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
            ->where('subscriber_id', '=', Auth::user()->subscriber_id)
            ->groupBy("productName")
            ->get();

        if ($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
                'data1' => $data1,
                'data2' => $data2,
                'message' => 'All select',
            ]);
        }
    }

    public function showReport(Request $request)
    {

        if ($request->filled('startdate') && $request->filled('enddate') && ($request->input('employee') == 'option_select')) {

            $from = date($request->startdate);
            $to = date($request->enddate);

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();


            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => "From and to"
                ]);
            }
        } elseif ($request->filled('startdate') && ($request->input('employee') == 'option_select') && ($request->input('enddate') == NULL)) {

            $from = date($request->startdate);

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->whereDate('created_at', '=', $from)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->whereDate('created_at', '=', $from)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->whereDate('created_at', '=', $from)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => "From",
                ]);
            }
        } elseif ($request->filled('enddate') && ($request->input('employee') == 'option_select') && ($request->input('startdate') == NULL)) {

            $to = date($request->enddate);

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->whereDate('created_at', '=', $to)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->whereDate('created_at', '=', $to)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->whereDate('created_at', '=', $to)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => 'To',
                ]);
            }
        } elseif ($request->filled('employee') && ($request->input('startdate') == NULL)  && ($request->input('enddate') == NULL && ($request->input('employee') != 'option_select'))) {

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => 'Emp!',
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('startdate') && ($request->input('enddate') == NULL)) {

            $from = date($request->startdate);

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->whereDate('created_at', '=', $from)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->whereDate('created_at', '=', $from)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->whereDate('created_at', '=', $from)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => 'Emp & From',
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('enddate') && ($request->input('startdate') == NULL)) {

            $to = date($request->enddate);

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->whereDate('created_at', '=', $to)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->whereDate('created_at', '=', $to)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->whereDate('created_at', '=', $to)
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => 'Emp & to',
                ]);
            }
        } elseif ($request->filled('employee') && $request->filled('enddate') && $request->filled('startdate')) {

            $from = date($request->startdate);
            $to = date($request->enddate);

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['salesBy_id', '=', $request->employee],
                    ['subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => 'From & To & Emp',
                ]);
            }
        } elseif (($request->input('employee') == 'option_select') && ($request->input('enddate') == NULL) && ($request->input('startdate') == NULL)) {

            $data = DB::table("ordered_products")
                ->select(DB::raw("SUM(quantity) as qty"), "productName")
                ->where('subscriber_id', '=', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("qty", "desc")
                ->take(5)
                ->get();

            $data1 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), "productName")
                ->where('subscriber_id', '=', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->orderBy("gt", "desc")
                ->get();

            $data2 = DB::table("ordered_products")
                ->select(DB::raw("SUM(grandTotal) as gt"), DB::raw("SUM(quantity) as qty"), DB::raw("SUM(totalDiscount) as td"), DB::raw("SUM(totalTax) as tt"), DB::raw("SUM(totalPrice) as tp"), "productName")
                ->where('subscriber_id', '=', Auth::user()->subscriber_id)
                ->groupBy("productName")
                ->get();

            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                    'data1' => $data1,
                    'data2' => $data2,
                    'message' => 'All select',
                ]);
            }
        }
    }
}
