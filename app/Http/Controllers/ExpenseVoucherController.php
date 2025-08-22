<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Log;

use App\Models\Supplier;
use App\Models\ChartOfAccount;
use App\Models\PaymentMethod;
use App\Models\Bank;
use App\Models\Transaction;

class ExpenseVoucherController extends Controller
{
    public function index(){
        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $income = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 4]
            ])->get();

        $cashInHand = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', 1010202]
            ])->get();

        $banks = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 1010201],
            ])->get();

        $customerX = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 1010101]
            ])->get();

        $pettyCash = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', 1010203]
            ])->get();

        $data = $cashInHand->concat($banks)->concat($pettyCash);

        $expenseAccounts = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 3],
                ['head_code','!=','309']
            ])->get();

        $mobileBanks = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $banks = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('expense-voucher/expense-voucher', ['suppliers' => $suppliers, 'assetAccounts' => $data,
                'datas' => $customerX, 'expenseAccounts' => $expenseAccounts]);
    }

    public function store(Request $request){
        $creditHeadCode = NUll;
        foreach($request->voucherHeads as $vh){
            if($vh['type'] === 'credit'){
                $creditHeadCode = $vh['headCode'];
                break;
            }

        }

        foreach($request->voucherHeads as $voucherHead){

            $coa = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', $voucherHead['headCode']]
            ])->first();

            // Log::info($coa);

            $transaction = New Transaction;
            $transaction->transaction_id = $request->transactionId;
            $transaction->head_code =  $coa->head_code;
            $transaction->head_name = $coa->head_name;
            $transaction->head_type = $coa->head_type;
            $transaction->reference_id = $creditHeadCode;
            $transaction->reference_note = $request->referenceNote;
            $transaction->transaction_date = $request->transactionDate;
            $transaction->transaction_type  = $voucherHead['transaction_type'] ?? 'N/A';
            $transaction->voucher = $request->voucher;


            if($voucherHead['type'] == "debit"){
                $transaction->debit = doubleval($voucherHead['amount']);
                $transaction->credit = 0;

                $lastBalance = Transaction::where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['head_code', $voucherHead['headCode']]
                ])->latest()->first();

                if($lastBalance){
                    $transaction->balance = $lastBalance->balance + doubleval($voucherHead['amount']);
                }else{
                    $transaction->balance = doubleval($voucherHead['amount']) * (1);
                }
            }else{
                $transaction->debit = 0;
                $transaction->credit = doubleval($voucherHead['amount']);
                $lastBalance = Transaction::where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['head_code', $voucherHead['headCode']]
                ])->latest()->first();

                if($lastBalance){
                    $transaction->balance = $lastBalance->balance - doubleval($voucherHead['amount']);
                }else{
                    $transaction->balance = doubleval($voucherHead['amount']) * (-1);
                }
            }


            $transaction->subscriber_id = Auth::user()->subscriber_id;
            $transaction->store_id = Auth::user()->store_id;

            $transaction->save();
        }


        return response() -> json([
            'status'=>200,
            'message' => 'Success!'
        ]);
    }

    public function view()
    {
        return view('expense-voucher/expense-voucher-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate, $voucher)
    {
        if ($transaction_id == 0) {
            $expense = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher', $voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $expense = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher', '=', $voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'expense' => $expense,
            'message' => 'Success!'
        ]);
    }
}
