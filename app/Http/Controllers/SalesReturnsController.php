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
use App\Models\ProductReturn;
use App\Models\ProductReturnDetail;
use App\Models\Requisition;
use Illuminate\Support\Facades\Log;


class SalesReturnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $productReturns =  ProductReturn::where('posting_status',0)->get();
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

    return view('sales_returns.index', [
        'suppliers' => $suppliers,
        'assetAccounts' => $data,
        'datas' => $income,
        'productReturns' => $productReturns,
    ]);
    }

     public function getDistributorCOA(Request $request)
    {

        $productReturnId = ProductReturnDetail::where('return_id',$request->return_id)->first();
    
        if (!$productReturnId) {
            return response()->json(['status' => 404, 'message' => 'Return ID not found']);
        }
    
        $distributor = Distributor::find($productReturnId->distributor_id);
    
        if (!$distributor) {
            return response()->json(['status' => 404, 'message' => 'Distributor not found']);
        }
        $requisition = Requisition::where('requisition_id',$productReturnId->order_id)->first();   
        $coa = ChartOfAccount::where('head_code', $distributor->head_code)->first();
    
        if (!$coa) {
            return response()->json(['status' => 404, 'message' => 'Chart of Account not found']);
        }
          
        return response()->json([
            'status' => 200,
            'head_name' => $coa->head_name,
            'head_code' => $coa->head_code,
            'requisition_id' => $requisition->requisition_id,
            'requisition_amount' => $requisition->total_amount
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $transaction->reference_id = $distributorHeadCode; 
        $transaction->reference_note = $request->referenceNote;
        $transaction->transaction_date = $request->transactionDate;
        $transaction->transaction_type = $voucherHead['transaction_type'] ?? 'Product Return';
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

    $productReturnId = $request->productReturnId;

if (!$productReturnId) {
    return response()->json(['status' => 422, 'message' => 'Product Return ID missing.']);
}

$productReturn = ProductReturn::find($productReturnId);

if (!$productReturn) {
    return response()->json(['status' => 404, 'message' => 'Product Return not found.']);
}

$productReturn->posting_status = 1;
if ($productReturn->isDirty()) {
    $productReturn->save();
}

    
    return response()->json([
        'status' => 200,
        'message' => 'Success!'
    ]);
    }

     public function view()
    {
        return view('sales_returns.sales-return-report');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $transaction_id, $startdate, $enddate,$voucher)
    {
         if ($transaction_id == 0) {
            $sales_return = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher','=',$voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $sales_return = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher','=',$voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'sales_return' => $sales_return,
            'message' => 'Success!'
        ]);
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
