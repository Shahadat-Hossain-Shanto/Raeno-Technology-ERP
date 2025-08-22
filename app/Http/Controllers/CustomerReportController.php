<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerReportController extends Controller
{
    public function transectionView()
    {
        $customers = Client::all();
        return view('customer/customer-transection-report', compact('customers'));
    }

    public function transectionData(Request $request, $head_code, $startdate, $enddate)
    {
        $customer = Client::find($head_code);
        $data =   $data = DB::table('deposits')
        ->select('deposit_date', 'deposit', 'due', 'deposit_type', 'balance', 'order_id')
        ->where('client_id', '=', $head_code)
        ->whereBetween('deposit_date', [$startdate, $enddate])
        ->get();

        log::info($data);
        if ($request->ajax()) {
            return response()->json([
                'status' => 200,
                'data' => $data,
                'customer' => $customer,
                'message' => 'Success!'
            ]);
        }
    }
}
