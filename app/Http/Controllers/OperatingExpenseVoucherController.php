<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use App\Models\ChartOfAccount;
use App\Models\PaymentMethod;
use App\Models\Bank;
use App\Models\Transaction;
use App\Models\Distributor;
use Illuminate\Support\Facades\Log;

class OperatingExpenseVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $distributors = ChartOfAccount::where([
            ['subscriber_id',Auth::user()->subscriber_id],
            ['parent_head_level', 1010104]
        ])->get();

        $data = $cashInHand->concat($banks)->concat($pettyCash)->concat($distributors);

        $expenseAccounts = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 309]
            ])->get();

        $mobileBanks = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $banks = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('operating-expense-voucher.index', ['suppliers' => $suppliers, 'assetAccounts' => $data,
                'datas' => $customerX, 'expenseAccounts' => $expenseAccounts]);
    }

    public function store(Request $request)
{
    $creditHeadCode = null;
    Log::info($request->all());
    // Find the credit head (used as reference_id)
    foreach ($request->voucherHeads as $voucherHead) {
        if ($voucherHead['type'] === 'credit') {
            $creditHeadCode = $voucherHead['headCode'];
            break;
        }
    }

    foreach ($request->voucherHeads as $voucherHead) {

        $coa = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['head_code', $voucherHead['headCode']]
        ])->first();

        $transaction = new Transaction;
        $transaction->transaction_id = $request->transactionId;
        $transaction->head_code = $coa->head_code;
        $transaction->head_name = $coa->head_name;
        $transaction->head_type = $coa->head_type;

        // Reference ID from credit head code
        $transaction->reference_id = $creditHeadCode;

        $transaction->reference_note = $request->referenceNote;
        $transaction->transaction_date = $request->transactionDate;
        $transaction->transaction_type = $voucherHead['transaction_type'] ?? 'Commission';
        $transaction->voucher = $request->voucher;


        if (empty($transaction->transaction_type)) {
            $transaction->transaction_type = 'Commission';
            Log::warning('transaction_type was empty or null. Using default "Commission".', $voucherHead);
        }

        $amount = doubleval($voucherHead['amount']);
        $lastBalance = Transaction::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['head_code', $voucherHead['headCode']]
        ])->latest()->first();

        if ($voucherHead['type'] == "debit") {
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

        // Update distributor balance if this is the credit head
        if ($voucherHead['type'] === 'credit' && $creditHeadCode) {
            $distributor = Distributor::where('head_code', $creditHeadCode)->first();
            if ($distributor) {
                $distributor->balance = $transaction->balance * -1;
                $distributor->save();
            }
        }
    }

    return response()->json([
        'status' => 200,
        'message' => 'Success!'
    ]);
}


    public function view()
    {
        return view('operating-expense-voucher.operating-expense-voucher-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate, $voucher)
    {
        if ($transaction_id == 0) {
            $expense = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher','=',$voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $expense = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher','=',$voucher)
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
