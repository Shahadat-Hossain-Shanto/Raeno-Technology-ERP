<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GeneralLedger extends Controller
{
    public function index()
    {
        $data = DB::table("transactions")
            ->where('subscriber_id', Auth::user()->subscriber_id)
            // ->distinct('head_name')
            ->get()->unique('head_name');
        // log::info($data);
        return view('general-ledger/general-ledger', ['heads' => $data]);
    }
    public function data(Request $request, $headcode, $startdate, $enddate)
    {
        if($headcode == 0) {
            $general_ledger = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        elseif($startdate==0||$enddate==0)
        {
            $general_ledger = Transaction::where('head_code', '=', $headcode)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        else
        {
            $general_ledger = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('head_code', '=', $headcode)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($headcode);
        // log::info($startdate);
        // log::info($enddate);
        // log::info($general_ledger);
        return response()->json([
            'status' => 200,
            'general_ledgers' => $general_ledger,
            'message' => 'Success!'
        ]);
    }
}
