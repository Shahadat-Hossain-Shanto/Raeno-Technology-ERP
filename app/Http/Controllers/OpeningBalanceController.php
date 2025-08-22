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

class OpeningBalanceController extends Controller
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
        $AccountReceivables = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level', '10101'],
            ['head_code', '!=', '1010101'],
            ['head_code','!=','1010104']
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

        $distributorReceivables = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level','1010104']
        ])->get();

        $operatingExpense = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['parent_head_level','309']
        ])->get();
        return view('opening-balance/opening-balance', ['customerReceivables' => $customerReceivables,'AccountReceivables' => $AccountReceivables,'cashAtBanks' => $cashAtBanks,'cashEquivalents' => $cashEquivalents,'inventorys' => $inventorys,'tassets' => $tassets, 'equities' => $equities, 'expenseAccounts' => $expenseAccounts, 'incomes' => $incomes, 'accountPayables' => $accountPayables, 'currentLiabilities' => $currentLiabilities, 'nonCurrentLiabilities' => $nonCurrentLiabilities,'distributorReceivables' => $distributorReceivables,'operatingExpenses' => $operatingExpense]);

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


            if($voucherHead['creditAmount'] == NULL || $voucherHead['creditAmount'] == 0){
                $transaction->debit = doubleval($voucherHead['debitAmount']);
                $transaction->credit = 0;
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
        return view('opening-balance/opening-balance-report');
    }
    public function show(Request $request, $transaction_id, $startdate, $enddate, $voucher)
    {
        if ($transaction_id == 0) {
            $opening_balance = Transaction::whereBetween('transaction_date', [$startdate, $enddate])
                ->where('voucher', '=', $voucher )
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $opening_balance = Transaction::where('transaction_id', '=', $transaction_id)
                ->where('voucher', '=', $voucher)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // log::info($purchase);
        return response()->json([
            'status' => 200,
            'opening_balance' => $opening_balance,
            'message' => 'Success!'
        ]);
    }
}
