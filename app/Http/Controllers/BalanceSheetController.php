<?php

namespace App\Http\Controllers;

use DB;
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

class BalanceSheetController extends Controller
{
    public function index(){
        return view('balance-sheet/balance-sheet');
    }

    public function data(Request $request){

        //Account Receivable
        $customerReceivables = DB::table("transactions")
        ->join("chart_of_accounts", function ($join) {
            $join->on("transactions.head_code", "=", "chart_of_accounts.head_code")
                 ->on("transactions.subscriber_id", "=", "chart_of_accounts.subscriber_id"); // Additional condition
        })
                ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
                ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['chart_of_accounts.parent_head_level', 1010101]])
                ->orderBy('transactions.head_code', 'asc')
                ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
                // ->take(5)
                ->get();

        $totalCreditCustomerReceivable = 0;
        $totalDebitCustomerReceivable = 0;
        foreach($customerReceivables as $customerReceivable){
            $totalCreditCustomerReceivable = $totalCreditCustomerReceivable + $customerReceivable->totalCredit;
            $totalDebitCustomerReceivable = $totalDebitCustomerReceivable + $customerReceivable->totalDebit;
        }


        $loanReceivables = DB::table("transactions")
                // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
                ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
                ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 1010102]])
                ->orderBy('transactions.head_code', 'asc')
                ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
                // ->take(5)
                ->get();

        $totalCreditLoanReceivable = 0;
        $totalDebitLoanReceivable = 0;
        foreach($loanReceivables as $loanReceivable){
            $totalCreditLoanReceivable = $totalCreditLoanReceivable + $loanReceivable->totalCredit;
            $totalDebitLoanReceivable = $totalDebitLoanReceivable + $loanReceivable->totalDebit;
        }

        $serviceReceivables = DB::table("transactions")
                // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
                ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
                ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 1010103]])
                ->orderBy('transactions.head_code', 'asc')
                ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
                // ->take(5)
                ->get();

        $totalCreditServiceReceivable = 0;
        $totalDebitServiceReceivable = 0;
        foreach($serviceReceivables as $serviceReceivable){
            $totalCreditServiceReceivable = $totalCreditServiceReceivable + $serviceReceivable->totalCredit;
            $totalDebitServiceReceivable = $totalDebitServiceReceivable + $serviceReceivable->totalDebit;
        }

        $totalCreditAccountReceivable = $totalCreditCustomerReceivable + $totalCreditLoanReceivable + $totalCreditServiceReceivable;
        $totalDebitAccountReceivable = $totalDebitCustomerReceivable + $totalDebitLoanReceivable +$totalDebitServiceReceivable;


        $totalBalanceAccountReceivable = $totalDebitAccountReceivable - $totalCreditAccountReceivable;
        $accountReceivableHeadName = 'Account Receivable';

        $x = ['amount'=>$totalBalanceAccountReceivable, 'head_name'=>$accountReceivableHeadName];
        $xxx[] = $x;



        //Cash & Cash Equivalent
        $cashAtBanks = DB::table("transactions")
        ->join("chart_of_accounts", function ($join) {
            $join->on("transactions.head_code", "=", "chart_of_accounts.head_code")
                 ->on("transactions.subscriber_id", "=", "chart_of_accounts.subscriber_id"); // Additional condition
        })
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['chart_of_accounts.parent_head_level', 1010201]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditCashAtBank = 0;
        $totalDebitCashAtBank = 0;
        foreach($cashAtBanks as $cashAtBank){
            $totalCreditCashAtBank = $totalCreditCashAtBank + $cashAtBank->totalCredit;
            $totalDebitCashAtBank = $totalDebitCashAtBank + $cashAtBank->totalDebit;
        }

        $cashInHandCashs = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 1010202]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditCashInHandCash = 0;
        $totalDebitCashInHandCash = 0;
        foreach($cashInHandCashs as $cashInHanCash){
            $totalCreditCashInHandCash = $totalCreditCashInHandCash + $cashInHanCash->totalCredit;
            $totalDebitCashInHandCash = $totalDebitCashInHandCash + $cashInHanCash->totalDebit;
        }

        $pettyCashs = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 1010203]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditPettyCash = 0;
        $totalDebitPettyCash = 0;
        foreach($pettyCashs as $pettyCash){
            $totalCreditPettyCash = $totalCreditPettyCash + $pettyCash->totalCredit;
            $totalDebitPettyCash = $totalDebitPettyCash + $pettyCash->totalDebit;
        }

        $totalCreditCashandCashEquivalent = $totalCreditCashAtBank + $totalCreditCashInHandCash + $totalCreditPettyCash;
        $totalDebitCashandCashEquivalent = $totalDebitCashAtBank + $totalDebitCashInHandCash + $totalDebitPettyCash;

        $totalBalanceCashandCashEquivalent = $totalDebitCashandCashEquivalent - $totalCreditCashandCashEquivalent;
        $cashandCashEquivalent = 'Cash & Cash Equivalent';

        $y = ['amount'=>$totalBalanceCashandCashEquivalent, 'head_name'=>$cashandCashEquivalent];
        $xxx[] = $y;


        //TOTAL CURRENT ASSET
        $totalCurrentAsset = $totalBalanceAccountReceivable + $totalBalanceCashandCashEquivalent;


        //Inventory
        $inventories = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 10201]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditInventory = 0;
        $totalDebitInventory = 0;
        foreach($inventories as $inventory){
            $totalCreditInventory = $totalCreditInventory + $inventory->totalCredit;
            $totalDebitInventory = $totalDebitInventory + $inventory->totalDebit;
        }

        $totalBalanceInventory =  $totalDebitInventory - $totalCreditInventory;
        $inventory = 'Inventory';

        $z = ['amount'=>$totalBalanceInventory, 'head_name'=>$inventory];
        $xxx[] = $z;

        //TOTAL NON CURRENT ASSET
        $totalNonCurrentAsset = $totalBalanceInventory;

        $totalCurrentNonCurrentAsset = $totalCurrentAsset + $totalNonCurrentAsset;

        //Tassets
        $tassets = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 103]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditTassets = 0;
        $totalDebitTassets = 0;
        foreach($tassets as $tasset){
            $totalCreditTassets = $totalCreditTassets + $tasset->totalCredit;
            $totalDebitTassets = $totalDebitTassets + $tasset->totalDebit;
        }

        $totalBalanceTasset =  $totalDebitTassets - $totalCreditTassets;
        $tasset = 'Tassets';

        $z = ['amount'=>$totalBalanceTasset, 'head_name'=>$tasset];
        $xxx[] = $z;

        //TOTAL Tassets
        $totalTasset = $totalBalanceTasset;

        $totalAsset = $totalCurrentNonCurrentAsset + $totalTasset;

        //LIABILITIES
        $accountPayables = DB::table("transactions")
        ->join("chart_of_accounts", function ($join) {
            $join->on("transactions.head_code", "=", "chart_of_accounts.head_code")
                 ->on("transactions.subscriber_id", "=", "chart_of_accounts.subscriber_id"); // Additional condition
        })
                ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
                ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['chart_of_accounts.parent_head_level', 50101]])
                ->orderBy('transactions.head_code', 'asc')
                ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
                // ->take(5)
                ->get();

        // Log::info($accountPayables);

        $totalCreditAccountPayables = 0;
        $totalDebitAccountPayables = 0;
        foreach($accountPayables as $accountPayable){
            $totalCreditAccountPayables = $totalCreditAccountPayables + $accountPayable->totalCredit;
            $totalDebitAccountPayables = $totalDebitAccountPayables + $accountPayable->totalDebit;
        }

        $totalBalanceAccountPayable = $totalDebitAccountPayables - $totalCreditAccountPayables;
        $accountPayable = 'Account Payable';

        $a = ['amount'=>$totalBalanceAccountPayable, 'head_name'=>$accountPayable];
        $yyy[] = $a;

        //EMPLOYEE PAYABLE
        $employeePayables = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 50102]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditEmployeePayable = 0;
        $totalDebitEmployeePayable = 0;
        foreach($employeePayables as $employeePayable){
            $totalCreditEmployeePayable = $totalCreditEmployeePayable + $employeePayable->totalCredit;
            $totalDebitEmployeePayable = $totalDebitEmployeePayable + $employeePayable->totalDebit;
        }

        $totalBalanceEmployeePayable = $totalDebitEmployeePayable - $totalCreditEmployeePayable;
        $emploeePayable = 'Employee Payable';

        $b = ['amount'=>$totalBalanceEmployeePayable, 'head_name'=>$emploeePayable];
        $yyy[] = $b;

         //SUPPLIER PAYABLE
        $supplierPayables = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 50103]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditSupplierPayable = 0;
        $totalDebitSupplierPayable = 0;
        foreach($supplierPayables as $supplierPayable){
            $totalCreditSupplierPayable = $totalCreditSupplierPayable + $supplierPayable->totalCredit;
            $totalDebitSupplierPayable = $totalDebitSupplierPayable + $supplierPayable->totalDebit;
        }

        $totalBalanceSupplierPayable = $totalDebitSupplierPayable - $totalCreditSupplierPayable;
        $supplierPayable = 'Supplier Payable';

        $c = ['amount'=>$totalBalanceSupplierPayable, 'head_name'=>$supplierPayable];
        $yyy[] = $c;

        //TAX PAYABLE
        $taxPayables = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 50104]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditTaxPayable = 0;
        $totalDebitTaxPayable = 0;
        foreach($taxPayables as $taxPayable){
            $totalCreditTaxPayable = $totalCreditTaxPayable + $taxPayable->totalCredit;
            $totalDebitTaxPayable = $totalDebitTaxPayable + $taxPayable->totalDebit;
        }

        $totalBalanceTaxPayable = $totalDebitTaxPayable - $totalCreditTaxPayable;
        $taxPayable = 'Tax Payable';

        $d = ['amount'=>$totalBalanceTaxPayable, 'head_name'=>$taxPayable];
        $yyy[] = $d;

        $totalCurrentLiabilities = $totalBalanceAccountPayable + $totalBalanceEmployeePayable + $totalBalanceSupplierPayable + $totalBalanceTaxPayable;


        //Non Current Liabilities
        $nonCurrentLiabilities = DB::table("transactions")
            // ->join("chart_of_accounts", "transactions.head_code", "chart_of_accounts.head_code")
            ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
            ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['head_code', 502]])
            ->orderBy('transactions.head_code', 'asc')
            ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
            // ->take(5)
            ->get();

        $totalCreditNonCurrentLiability = 0;
        $totalDebitNonCurrentLiability = 0;
        foreach($nonCurrentLiabilities as $nonCurrentLiability){
            $totalCreditNonCurrentLiability = $totalCreditNonCurrentLiability + $nonCurrentLiability->totalCredit;
            $totalDebitNonCurrentLiability = $totalDebitNonCurrentLiability + $nonCurrentLiability->totalDebit;
        }

        $totalBalanceNonCurrentLiability = $totalDebitNonCurrentLiability - $totalCreditNonCurrentLiability;
        $nonCurrentLiability = 'Non Current Liabilities';

        $d = ['amount'=>$totalBalanceNonCurrentLiability, 'head_name'=>$nonCurrentLiability];
        $yyy[] = $d;

        $totalNonCurrentLiabilities = $totalBalanceNonCurrentLiability;

        $totalLiability = $totalCurrentLiabilities + $totalNonCurrentLiabilities;


        //EQUITY
        $equitys = DB::table("transactions")
        ->join("chart_of_accounts", function ($join) {
            $join->on("transactions.head_code", "=", "chart_of_accounts.head_code")
                 ->on("transactions.subscriber_id", "=", "chart_of_accounts.subscriber_id"); // Additional condition
        })
                ->select(DB::raw("SUM(transactions.debit) as totalDebit"), DB::raw("SUM(transactions.credit) as totalCredit"), 'transactions.head_code', 'transactions.head_name', 'transactions.head_type')
                // ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['chart_of_accounts.parent_head_level', 2]])
                ->where([['transactions.subscriber_id', Auth::user()->subscriber_id], ['chart_of_accounts.parent_head_level', 2]])
                ->orderBy('transactions.head_code', 'asc')
                ->groupBy("transactions.head_code", "transactions.head_name", "transactions.head_type")
                // ->take(5)
                ->get();

        // Log::info($equitys);

        $totalCreditEquity = 0;
        $totalDebitEquity = 0;
        foreach($equitys as $equity){
            $totalCreditEquity = $totalCreditEquity + $equity->totalCredit;
            $totalDebitEquity = $totalDebitEquity + $equity->totalDebit;
        }

        $totalBalanceEquity = $totalDebitEquity - $totalCreditEquity;
        $equity = 'Equity';


        $p = ['amount'=>$totalBalanceEquity, 'head_name'=>$equity];
        $zzz[] = $p;





        //-------------------------------------------INCOME-------------------------------------------
        $data = DB::table("transactions")
                ->select(DB::raw("SUM(debit) as totalDebit"), DB::raw("SUM(credit) as totalCredit"), 'head_code', 'head_name', 'head_type')
                ->where([['subscriber_id', Auth::user()->subscriber_id], ['head_type', 'I']])
                ->orderBy('head_code', 'asc')
                ->groupBy("head_code", "head_name", "head_type")
                // ->take(5)
                ->get();

        $totalRevenue = 0;
        if($data){
            foreach($data as $d){
                $totalRevenue = $totalRevenue + $d->totalCredit;
                // Log::info($total);
            }
        }else{
            $totalRevenue = 0;
        }


        $data1 = DB::table("transactions")
                ->select(DB::raw("SUM(debit) as totalDebit"), DB::raw("SUM(credit) as totalCredit"), 'head_code', 'head_name', 'head_type')
                ->where([['subscriber_id', Auth::user()->subscriber_id], ['head_type', 'E']])
                ->orderBy('head_code', 'asc')
                ->groupBy("head_code", "head_name", "head_type")
                // ->take(5)
                ->get();

         $totalExpense = 0;
        if($data1){
            foreach($data1 as $d){
                $totalExpense = $totalExpense + $d->totalDebit;
                // Log::info($total);
            }
        }else{
            $totalExpense = 0;
        }

        $totalBalanceIncome = $totalRevenue - $totalExpense;
        $income = 'Income';


        $q = ['amount'=>$totalBalanceIncome, 'head_name'=>$income];
        $zzz[] = $q;


        $totalIncome = $totalBalanceIncome;
        $totalEquity = $totalBalanceEquity + $totalIncome;

        if($request -> ajax()){
            return response()->json([
                'dataX' => $xxx,
                'dataY' => $yyy,
                'dataZ' => $zzz,
                'totalAsset'=> $totalAsset,
                'totalLiability'=> $totalLiability,
                'totalEquity'=> $totalEquity,
                'totalEquityLiability' => $totalLiability + $totalEquity,
                'totalIncome' => $totalIncome,
                'total'=>$totalAsset - ( $totalLiability + $totalEquity ),

                'message'=>'Success'
            ]);
        }

    }
}
