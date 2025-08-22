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

class SupplierPaymentController extends Controller
{
    public function index(){

        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $expenseAccounts = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 3]
            ])->get();

        $cashInHand = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', 1010202]
            ])->get();

        $banks = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 1010201],
            ])->get();

        $suppliersX = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 50101]
            ])->get();

        $pettyCash = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', 1010203]
            ])->get();

        $data = $cashInHand->concat($banks)->concat($pettyCash);

        return view('supplier-payment/supplier-payment', ['suppliers' => $suppliers, 'expenseAccounts' => $expenseAccounts,
                'datas' => $data, 'supplierX' =>  $suppliersX]);
    }

    public function store(Request $request){


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
            $transaction->reference_id = $request->poNumber;
            $transaction->reference_note = $request->referenceNote;
            $transaction->transaction_date = $request->transactionDate;
            $transaction->transaction_type  = $request->transaction_type;

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
        return view('supplier-payment/supplier-payment-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate, $transaction_type)
    {
        if ($transaction_id == 0) {
            $supplier_payments = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('transaction_type', '=', $transaction_type)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $supplier_payments = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('transaction_type', '=', $transaction_type)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'supplier_payments' => $supplier_payments,
            'message' => 'Success!'
        ]);
    }
}
