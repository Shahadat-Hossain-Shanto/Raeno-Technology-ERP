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
use App\Models\Requisition;
use App\Models\Distributor;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class SalesVoucherController extends Controller
{
    public function index(){
       
        $requisitions = Requisition::where('status',1)->where('posting',0)->get();
        
      
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

        $distributorX = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', 10101]
            ])->get();

        $pettyCash = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', 1010203]
            ])->get();
 

        $data = $cashInHand->concat($banks)->concat($distributorX)->concat($pettyCash);
        // Log::info($data);

        $mobileBanks = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $banks = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('sales-voucher/sales-voucher', ['suppliers' => $suppliers, 'assetAccounts' => $data,
                'datas' => $income, 'requisitions'=> $requisitions]);
    }

public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // Step 1: Fetch and validate requisition
        $requisition = Requisition::where('requisition_id', $request->requisitionId)->first();

        if (!$requisition) {
            return response()->json(['status' => 404, 'message' => 'Requisition not found']);
        }

        // Step 2: Prevent duplicate posting
        if ($requisition->posting == 1) {
            return response()->json(['status' => 409, 'message' => 'Requisition already posted']);
        }

        // Step 3: Get distributor
        $distributor = Distributor::find($requisition->distributor_id);

        // Step 4: Get distributor head code from credit entry
        $distributorHeadCode = null;
        foreach ($request->voucherHeads as $voucherHead) {
            if ($voucherHead['type'] === 'debit') {
                $distributorHeadCode = $voucherHead['headCode'];
                break;
            }
        }

        // Step 5: Process each voucher head
        foreach ($request->voucherHeads as $voucherHead) {
            $amount = doubleval($voucherHead['amount']);

            $coa = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', $voucherHead['headCode']]
            ])->first();

            if (!$coa) {
                DB::rollBack();
                return response()->json(['status' => 404, 'message' => 'Chart of Account not found']);
            }

            $transaction = new Transaction;
            $transaction->transaction_id = $request->transactionId;
            $transaction->head_code = $coa->head_code;
            $transaction->head_name = $coa->head_name;
            $transaction->head_type = $coa->head_type;

            // Store distributor head code as reference_id
            $transaction->reference_id = $distributorHeadCode;
            $transaction->reference_note = $request->referenceNote;
            $transaction->transaction_date = $request->transactionDate;
            $transaction->transaction_type = $voucherHead['transaction_type'] ?? 'Product Sell';
            $transaction->voucher = $request->voucher;
            $transaction->subscriber_id = Auth::user()->subscriber_id;
            $transaction->store_id = Auth::user()->store_id;

            // Get last balance
            $lastBalance = Transaction::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', $voucherHead['headCode']]
            ])->latest()->first();

            // Debit or Credit Logic
            if ($voucherHead['type'] === 'debit') {
                $transaction->debit = $amount;
                $transaction->credit = 0;
                $transaction->balance = $lastBalance ? $lastBalance->balance + $amount : $amount;

                if ($distributor) {
                    $distributor->balance = $transaction->balance * -1;
                    $distributor->save();
                }
            } else {
                $transaction->debit = 0;
                $transaction->credit = $amount;
                $transaction->balance = $lastBalance ? $lastBalance->balance - $amount : -1 * $amount;
            }

            $transaction->save();
        }

        // Step 6: Mark requisition as posted
        $requisition->posting = 1;
        $requisition->save();

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Success!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}



    public function view()
    {
        return view('sales-voucher/sales-voucher-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate,$voucher)
    {
        if ($transaction_id == 0) {
            $sales = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher','=',$voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $sales = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher','=',$voucher)              
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'sales' => $sales,
            'message' => 'Success!'
        ]);
    }
    public function getDistributorCOA(Request $request)
    {
        $requisition = Requisition::where('requisition_id', $request->requisition_id)->first();
    
        if (!$requisition) {
            return response()->json(['status' => 404, 'message' => 'Requisition not found']);
        }
    
        $distributor = Distributor::find($requisition->distributor_id);
    
        if (!$distributor) {
            return response()->json(['status' => 404, 'message' => 'Distributor not found']);
        }
    
        $coa = ChartOfAccount::where('head_code', $distributor->head_code)->first();
    
        if (!$coa) {
            return response()->json(['status' => 404, 'message' => 'Chart of Account not found']);
        }
        

        $requisition->posting = 1;

    
        return response()->json([
            'status' => 200,
            'head_name' => $coa->head_name,
            'head_code' => $coa->head_code,
            'total_amount' => $requisition->total_amount
        ]);
    }
    
}
