<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Bank;
use App\Models\Supplier;
use App\Models\Transaction;

use Illuminate\Http\Request;

use App\Models\PaymentMethod;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Distributor;

class JournalVoucherController extends Controller
{
    public function index(){

        // $assets = ChartOfAccount::where([
        //         ['subscriber_id', Auth::user()->subscriber_id],
        //         ['head_type', 'A']
        //     ])->get();

        $customerReceivables = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level', '1010101']
        ])->get();
    
    $distributorReceivables = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level', '1010104']
        ])->get();
    $AccountReceivables = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '10101'],
        ['head_code', '!=', '1010101'],
        ['head_code', '!=', '1010104'],
    ])->get();

    $cashAtBanks = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '1010201']
    ])->get();

    $cashEquivalents = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '10102'],
        ['head_code', '!=', '1010201']
    ])->get();

    $inventorys = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '102']
    ])->get();

    $tassets = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['head_code', '103']
    ])->get();


    $equities = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level', '2']
        ])->get();


    $expenseAccounts = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level', '3'],
            ['head_code','!=','309']
        ])->get();

    $operatingExpenses = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level','309']
    ])->get();


    $incomes = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '4']
    ])->get();

    // $liabilities = ChartOfAccount::where([
    //     ['subscriber_id', Auth::user()->subscriber_id],
    //     ['head_type', 'L']
    // ])->get();

    $accountPayables = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '50101']
    ])->get();
    $currentLiabilities = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['parent_head_level', '501'],
        ['head_code', '!=', '50101']
    ])->get();
    $nonCurrentLiabilities = ChartOfAccount::where([
        ['subscriber_id', Auth::user()->subscriber_id],
        ['head_code', '502']
    ])->get();

        return view('journal-voucher/journal-voucher', ['customerReceivables' => $customerReceivables,'AccountReceivables' => $AccountReceivables,'cashAtBanks' => $cashAtBanks,'cashEquivalents' => $cashEquivalents,'inventorys' => $inventorys,'tassets' => $tassets, 'equities' => $equities, 'expenseAccounts' => $expenseAccounts, 'incomes' => $incomes, 'accountPayables' => $accountPayables, 'currentLiabilities' => $currentLiabilities, 'nonCurrentLiabilities' => $nonCurrentLiabilities,'distributorReceivables' => $distributorReceivables,'operatingExpenses' => $operatingExpenses]);
    }

    public function store(Request $request){
        foreach($request->voucherHeads as $voucherHead){

            $coa = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', $voucherHead['headCode']]
            ])->first();

            $creditHeadName = NULL;
            foreach($request->voucherHeads as $vh){
                if(!empty($vh['creditAmount']) && $vh['creditAmount']>0){
                     $creditCoa = ChartOfAccount::where([
                        ['head_code',$vh['headCode']]
                    ])->first();
                    $creditHeadName= $creditCoa ? $creditCoa->head_name : null;
                    break;
                }
            }

            $transaction = New Transaction;
            $transaction->transaction_id = $request->transactionId;
            $transaction->head_code =  $coa->head_code;
            $transaction->head_name = $coa->head_name;
            $transaction->head_type = $coa->head_type;
            $transaction->reference_id = $creditCoa->head_code;
            $transaction->reference_note = $request->referenceNote;
            $transaction->transaction_date = $request->transactionDate;
            $transaction->voucher = $request->voucher;

           

            if($voucherHead['creditAmount'] == NULL || $voucherHead['creditAmount'] == 0){
                $transaction->debit = doubleval($voucherHead['debitAmount']);
                $transaction->credit = 0;
                $transaction->transaction_type = 'Adjustment Voucher To '.$creditHeadName;
                $lastBalance = Transaction::where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['head_code', $voucherHead['headCode']]
                ])->latest()->first();

                if($lastBalance){
                    $transaction->balance = $lastBalance->balance + doubleval($voucherHead['debitAmount']);
                }else{
                    $transaction->balance = doubleval($voucherHead['debitAmount']) * (1);
                }
            }else{
                $transaction->debit = 0;
                $transaction->credit = doubleval($voucherHead['creditAmount']);
                $transaction->transaction_type = 'Adjustment Voucher';
                $lastBalance = Transaction::where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['head_code', $voucherHead['headCode']]
                ])->latest()->first();

                if($lastBalance){
                    $transaction->balance = $lastBalance->balance - doubleval($voucherHead['creditAmount']);
                }else{
                    $transaction->balance = doubleval($voucherHead['creditAmount']) * (-1);
                }
            }

            if($voucherHead['debitAmount'] == NULL || $voucherHead['debitAmount'] == 0){                
                $distributor = Distributor::where('head_code',$voucherHead['headCode'])->first();
                if($distributor){
                    $distributor->balance = $transaction->balance * -1;
                    $distributor->save();
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
        return view('journal-voucher/journal-voucher-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate, $voucher)
    {
        if ($transaction_id == 0) {
            $journal_vouchers = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher', $voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $journal_vouchers = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher', $voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'journal_vouchers' => $journal_vouchers,
            'message' => 'Success!'
        ]);
    }
}
