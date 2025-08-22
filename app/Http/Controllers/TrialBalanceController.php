<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Bank;
use App\Models\Supplier;
use App\Models\Transaction;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TrialBalanceController extends Controller
{
    public function index()
    {
        return view('trial-balance/trial-balance');
    }

    public function data(Request $request)
    {

        $datas = DB::table("transactions")
            ->select(DB::raw("SUM(debit) as totalDebit"), DB::raw("SUM(credit) as totalCredit"), 'head_code', 'head_name')
            ->where('subscriber_id', Auth::user()->subscriber_id)
            ->orderBy('head_code', 'asc')
            ->groupBy("head_code", "head_name")
            // ->take(5)
            ->get();
        $totalDebit=0;
        $totalCredit=0;
        foreach($datas as $data)
        {
            $totalDebit=$totalDebit+$data->totalDebit;
            $totalCredit=$totalCredit+$data->totalCredit;
        }

        if ($request->ajax()) {
            return response()->json([
                'totalDebit' => $totalDebit,
                'totalCredit' => $totalCredit,
                'data' => $datas,
                'message' => 'Success!'
            ]);
        }
    }

    public function dateWise(Request $request)
    {
        $from = date($request->startdate);
        $to = date($request->enddate);

        if ($request->filled('startdate') && $request->input('enddate') == NULL) {

            $datas = DB::table("transactions")
                ->select(DB::raw("SUM(debit) as totalDebit"), DB::raw("SUM(credit) as totalCredit"), 'head_code', 'head_name')
                ->whereDate('transaction_date', '=', $from)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->orderBy('head_code', 'asc')
                ->groupBy("head_code", "head_name")
                // ->take(5)
                ->get();
                $totalDebit=0;
                $totalCredit=0;
                foreach($datas as $data)
                {
                    $totalDebit=$totalDebit+$data->totalDebit;
                    $totalCredit=$totalCredit+$data->totalCredit;
                }
            return response()->json([
                'status' => 200,
                'message' => "Only From date",
                'totalDebit' => $totalDebit,
                'totalCredit' => $totalCredit,
                'data' => $datas,
            ]);
        } elseif ($request->filled('enddate') && $request->input('startdate') == NULL) {
            $datas = DB::table("transactions")
                ->select(DB::raw("SUM(debit) as totalDebit"), DB::raw("SUM(credit) as totalCredit"), 'head_code', 'head_name')
                ->whereDate('transaction_date', '=', $to)
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->orderBy('head_code', 'asc')
                ->groupBy("head_code", "head_name")
                // ->take(5)
                ->get();
                $totalDebit=0;
                $totalCredit=0;
                foreach($datas as $data)
                {
                    $totalDebit=$totalDebit+$data->totalDebit;
                    $totalCredit=$totalCredit+$data->totalCredit;
                }
            return response()->json([
                'status' => 200,
                'message' => "Only to date",
                'totalDebit' => $totalDebit,
                'totalCredit' => $totalCredit,
                'data' => $datas,
            ]);
        } else {
            $datas = DB::table("transactions")
                ->select(DB::raw("SUM(debit) as totalDebit"), DB::raw("SUM(credit) as totalCredit"), 'head_code', 'head_name')
                // ->whereBetween('transaction_date', [$from, $to])
                ->whereBetween('transaction_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', Auth::user()->subscriber_id)
                ->orderBy('head_code', 'asc')
                ->groupBy("head_code", "head_name")
                // ->take(5)
                ->get();
                $totalDebit=0;
                $totalCredit=0;
                foreach($datas as $data)
                {
                    $totalDebit=$totalDebit+$data->totalDebit;
                    $totalCredit=$totalCredit+$data->totalCredit;
                }
            return response()->json([
                'status' => 200,
                'message' => "From and to date",
                'totalDebit' => $totalDebit,
                'totalCredit' => $totalCredit,
                'data' => $datas,
            ]);
        }
    }
}
