<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Supplier;
use App\Models\ChartOfAccount;
use App\Models\PaymentMethod;
use App\Models\Bank;
use App\Models\Distributor;
use App\Models\Transaction;


class DistributorDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function index(){
     $distributorReceivableParent = ChartOfAccount::where([
            ['head_name', 'Distributor Receivable'],
        ])->first();

        if (!$distributorReceivableParent) {
            return back()->with('error', 'Distributor Receivable head is missing in Chart of Accounts.');
        }
    $distributorNames =  ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', $distributorReceivableParent->head_code]
    ])->get();

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
        ['parent_head_level', 1010201]
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

    $mobileBanks = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();
    $banks = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();

    return view('distributor_deposit.index', [
        'suppliers' => $suppliers,
        'assetAccounts' => $data,
        'datas' => $customerX,
        'distributorNames' => $distributorNames
    ]);
}


public function store(Request $request)
{
    // First, extract the distributor head code from the credit voucher
    $distributorHeadCode = null;
    foreach ($request->voucherHeads as $voucherHead) {
        if ($voucherHead['type'] === 'credit') {
            $distributorHeadCode = $voucherHead['headCode'];
            break;
        }
    }

    foreach ($request->voucherHeads as $voucherHead) {
        $transaction = new Transaction;
        $transaction->transaction_id = $request->transactionId;
        $transaction->head_code = $voucherHead['headCode'];
        $transaction->head_name = $voucherHead['headName'];
        $transaction->reference_id = $distributorHeadCode; // ✅ store only distributor's head code
        $transaction->reference_note = $request->referenceNote;
        $transaction->transaction_date = $request->transactionDate;
        $transaction->transaction_type = $voucherHead['transaction_type'] ?? 'Distributor Deposit';
        $transaction->voucher = $request->voucher;



        $distributor = Distributor::where('head_code',$distributorHeadCode)->first();

        $lastBalance = Transaction::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['head_code', $voucherHead['headCode']]
        ])->latest()->first();

        $amount = doubleval($voucherHead['amount']);
        if ($voucherHead['type'] === "debit") {
            $transaction->debit = $amount;
            $transaction->credit = 0;
            $transaction->balance = $lastBalance ? $lastBalance->balance + $amount : $amount;
        } else {
            $transaction->debit = 0;
            $transaction->credit = $amount;
            $transaction->balance = $lastBalance ? $lastBalance->balance - $amount : -$amount;
        }

        $transaction->subscriber_id = Auth::user()->subscriber_id;
        $transaction->store_id = Auth::user()->store_id;
        $transaction->save();
          if ($voucherHead['type'] == "credit" && $distributor) {
                $distributor->balance = $transaction->balance * (-1);
                $distributor->save();
            }
        $distributor->save();
    }

    return response()->json([
        'status' => 200,
        'message' => 'Success!'
    ]);
}



    public function view()
    {
        return view('distributor_deposit.distributor-deposit-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate,$voucher)
    {
        if ($transaction_id == 0) {
            $distributor_deposit = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher','=',$voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $distributor_deposit = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher','=',$voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'distributor_deposit' => $distributor_deposit,
            'message' => 'Success!'
        ]);
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
