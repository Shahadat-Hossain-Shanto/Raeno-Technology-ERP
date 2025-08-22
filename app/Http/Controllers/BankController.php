<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Subscriber;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function create(){

        $banks = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id], 
            ['parent_head_level', 1010201]
        ])->get();

        return view('bank/bank-add', ['banks' => $banks]);
    }

    public function store(Request $request){

        $messages = [
            'bankname.required'  =>    "Bank name is required.",
            'accountname.required'  =>    "Account Name date is required.",
            'accountnumber.required'  =>    "Account Number is required.",
            'accounthead.required'  =>    "Account Head is required.",
            'status.required'  =>    "Status is required.",
        ];

        $validator = Validator::make($request->all(), [
            'bankname' => 'required',
            'accountname' => 'required',
            'accountnumber' => 'required|unique:banks,account_number',
            'accounthead' => [
                'required',
                Rule::unique('banks', 'account_head')
                    ->where('subscriber_id', Auth::user()->subscriber_id)
            ],
            'status' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $bank = new Bank;

            $bank->bank_name = $request->bankname;
            $bank->account_name = $request->accountname;
            $bank->account_number = $request->accountnumber;
            $bank->branch = $request->branch;
            $bank->account_head = $request->accounthead;

            if($request->openingbalance == NULL){
                $bank->balance = 0;
            }else{
                $bank->balance = doubleval($request->openingbalance);
            }
            
            $bank->status = $request->status;

            if ($request -> hasFile('imagefile')) {
                $file = $request -> file ('imagefile');
                
                
                $filename = time() . '.' .$file->getClientOriginalName();

                $file->move('uploads/banks/', $filename);
                $bank->sign_cheque_image = $filename;
            }

            
            $bank->subscriber_id = Auth::user()->subscriber_id;
            $bank->user_id = Auth::user()->id;

            $coa = ChartOfAccount::where('head_code', $request->accounthead)->first();
            $transaction = New Transaction;
            $transaction->transaction_id = "IB";
            $transaction->head_code =  $coa->head_code;
            $transaction->head_name = $coa->head_name;
            $transaction->head_type = $coa->head_type;
            $transaction->transaction_date = Carbon::now()->toDateString(); ;
            $transaction->transaction_type  = "New Account in Bank";

                $transaction->credit = 0;
                $transaction->debit = $request->openingbalance ?? 0;
                $lastBalance = Transaction::where([
                    ['subscriber_id', Auth::user()->subscriber_id],
                    ['head_code', $request->accounthead]
                ])->latest()->first();

                if($lastBalance){
                    $transaction->balance = $lastBalance->balance + doubleval($request->openingbalance);
                }else{
                    $transaction->balance = doubleval($request->openingbalance) * (1);
                }


            $transaction->subscriber_id = Auth::user()->subscriber_id;
            $transaction->store_id = Auth::user()->store_id;

            $transaction->save();
            // $coa = new ChartOfAccount;
            // $coa->head_code = ((int)$request->accounthead * 1000) + 1;
            // $coa->head_name = $request->accountname;
            // $coa->parent_head = $data->head_name;
            // $coa->parent_head_level = $request->accounthead;
            // $coa->head_type = $data->head_type;
            // $coa->is_transaction = $data->is_transaction;
            // $coa->is_active = $data->is_active;
            // $coa->is_general_ledger = $data->is_general_ledger;
            // $coa->subscriber_id = Auth::user()->subscriber_id;
            // $coa->save();

            $bank->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Bank created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function listView(){
        return view('bank/bank-list');
    }

    public function list(Request $request){

        $bank = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'bank'=>$bank,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $bank = Bank::find($id);

        if($bank){
            return response()->json([
                'status'=>200,
                'bank'=>$bank,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'bankname.required'  =>    "Bank name is required.",
            'accountname.required'  =>    "Account Name date is required.",
            'accountnumber.required'  =>    "Account Number is required.",
            // 'accounthead.required'  =>    "Account Head is required.",
            'status.required'  =>    "Status is required.",
        ];

        $validator = Validator::make($request->all(), [
            'bankname' => 'required',
            'accountname' => 'required',
            'accountnumber' => 'required',
            // 'accounthead' => 'required',
            'status' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $bank = Bank::find($id);

            $balance = $bank->balance;

            $bank->bank_name    = $request->bankname;
            $bank->account_name     = $request->accountname;
            $bank->account_number     = $request->accountnumber;
            $bank->branch     = $request->branch;
            $bank->balance     = doubleval($request->balance);
            // $bank->balance     = $balance + doubleval($request->balance);
            $bank->status     = $request->status;
            // $bank->sign_cheque_image     = $request->sign_cheque_image;

            if ($request -> hasFile('imagefile')) {

                if($bank->sign_cheque_image == NULL){
                    $file = $request -> file ('imagefile');
                    // $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' .$file->getClientOriginalName();
                    $file->move('uploads/banks/', $filename);
                    $bank->sign_cheque_image = $filename;

                }else{
                    $path = 'uploads/banks/'.$bank->sign_cheque_image;
                    if(File::exists($path)){
                        File::delete($path);
                    }

                    $file = $request -> file ('imagefile');
                    // $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' .$file->getClientOriginalName();
                    $file->move('uploads/banks/', $filename);
                    $bank->sign_cheque_image = $filename;
                }         
            }
            
            $bank->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Bank updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        Bank::find($id)->delete($id);
        return redirect('bank-list')->with('status', 'Deleted successfully!');
    }
}
