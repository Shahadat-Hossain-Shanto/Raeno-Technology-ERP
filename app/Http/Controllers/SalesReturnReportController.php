<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\User;
use App\Models\Store;
use App\Models\SalesReturn;
use App\Models\Order;

use Illuminate\Support\Facades\Auth;
use Log;
use DB;
use Carbon\Carbon;


class SalesReturnReportController extends Controller
{
    public function index()
    {

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('report/sales-return-report', ["stores" => $stores]);
    }

    public function onLoad(Request $request)
    {

        // $data = SalesReturn::join('orders', 'sales_returns.invoice_no', 'orders.orderId')
        // ->leftJoin('clients', 'orders.clientId', 'clients.id')
        // ->select('sales_returns.*', 'clients.name', 'clients.mobile')
        // ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)->get()->unique('sales_returns.return_number');

        $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
            ->leftJoin('clients', 'orders.clientId', 'clients.id')
            ->select('sales_returns.*', 'clients.name', 'clients.mobile')
            ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)
            ->get();
        // ->unique('sales_returns.return_number');

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        ]);
    }

    public function showData(Request $request)
    {

        $timestamp = Carbon::now();
        $date = $timestamp->toDateString();
        $from = date($request->startdate);
        $to = date($request->enddate);

        if ($request->filled('startdate') && $request->filled('enddate') && ($request->input('store') == 'option_select')) {

            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->whereBetween('sales_returns.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and to",
                'data' => $data
            ]);
        } elseif ($request->filled('startdate') && $request->filled('enddate') && ($request->input('store') != 'option_select')) {

            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->whereBetween('sales_returns.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['sales_returns.subscriber_id', Auth::user()->subscriber_id],
                    ['sales_returns.store_id', $request->store]
                ])
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From and to & store",
                'data' => $data
            ]);
        } elseif ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('store') == 'option_select')) {
            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->whereDate('sales_returns.created_at', $from)
                ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "From",
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->filled('enddate') && ($request->input('store') == 'option_select')) {
            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->whereDate('sales_returns.created_at', $to)
                ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "To",
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->input('enddate') == NULL && ($request->input('store') != 'option_select')) {
            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->where([
                    ['sales_returns.subscriber_id', Auth::user()->subscriber_id],
                    ['sales_returns.store_id', $request->store]
                ])
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "store",
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->input('enddate') == NULL && ($request->input('store') == 'option_select')) {
            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->where([
                    ['sales_returns.subscriber_id', Auth::user()->subscriber_id]
                ])
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "all store",
                'data' => $data
            ]);
        } elseif ($request->filled('startdate') && $request->input('enddate') == NULL && ($request->input('store') != 'option_select')) {
            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->whereDate('sales_returns.created_at', $from)
                ->where([
                    ['sales_returns.subscriber_id', Auth::user()->subscriber_id],
                    ['sales_returns.store_id', $request->store]
                ])
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "from and store",
                'data' => $data
            ]);
        } elseif ($request->input('startdate') == NULL && $request->filled('enddate') && ($request->input('store') != 'option_select')) {
            $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
                ->leftJoin('clients', 'orders.clientId', 'clients.id')
                ->select('sales_returns.*', 'clients.name', 'clients.mobile')
                ->whereDate('sales_returns.created_at', $to)
                ->where([
                    ['sales_returns.subscriber_id', Auth::user()->subscriber_id],
                    ['sales_returns.store_id', $request->store]
                ])
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "to and store",
                'data' => $data
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
    }
}
