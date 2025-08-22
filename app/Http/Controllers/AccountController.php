<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Supplier;

use App\Models\Subscriber;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(){

        $rootHeads = ChartOfAccount::where([
                ['parent_head_level', 0],
                ['parent_head', 'COA'],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->get();

        return view('account/add-account', ['rootHeads' => $rootHeads]);
    }

    public function purcahse_date($subscriberId) {
        $subscriber = Subscriber::where('id', $subscriberId)->first();

        if ($subscriber) {
            $createdDate = $subscriber->created_at;
            $carbonDate = Carbon::parse($createdDate);
            $sevenDaysLater = $carbonDate->addDays(7);


            return response()->json([
                'date' => $sevenDaysLater->toDateString(),
                'accountStatus'=>$subscriber->registration_type
            ]);
        } else {
            return response()->json([
                'error' => 'Subscriber not found'
            ], 404); // You may want to return a 404 status code for a not found resource
        }
    }

    public function getPaerntHead(Request $request, $rootHeadCode){
        $headType =  ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['head_code', $rootHeadCode]
        ])->first();

        $parentHeads = ChartOfAccount::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['head_type', $headType->head_type]
        ])->get();
        // $parentHeads = ChartOfAccount::where([
        //       ['subscriber_id', Auth::user()->subscriber_id],
        //       ['parent_head_level',$headType->head_code]
        // ])->get();

        return response() -> json([
            'status'=>200,
            'message' => 'Success!',
            'data' => $parentHeads
        ]);

    }

    public function store(Request $request){
         $messages = [
            'roothead.required'  =>    "Root head is required.",
            'parenthead.required'  =>    "Parent haed is required.",
            'headname.required'  =>    "Account or head name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'roothead' => 'required',
            'parenthead' => 'required',
            'headname' => 'required',
        ], $messages);

        if ($validator->passes()) {

            $findCoa = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', $request->parenthead]
            ])->first();


            $coa = new ChartOfAccount;

            $datas = ChartOfAccount::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['parent_head_level', $request->parenthead]
            ])->latest()->first();

            if($datas){
                $coa->head_code             = $datas->head_code + 1;
                // Log::info($datas->head_code + 1);

            }else{
                $coa->head_code             = ($findCoa->head_code * 100) + 1;
                // Log::info(($findCoa->head_code * 100) + 1);
            }

            $coa->head_name             = $request->headname;
            $coa->parent_head           = $findCoa->head_name;
            $coa->parent_head_level     = $request->parenthead;
            $coa->head_type             = $findCoa->head_type;
            $coa->is_transaction        = $findCoa->is_transaction;
            $coa->is_active             = $findCoa->is_active;
            $coa->is_general_ledger     = $findCoa->is_general_ledger;
            $coa->subscriber_id     = Auth::user()->subscriber_id;
            $coa->save();


            return response() -> json([
                'status'=>200,
                'message' => 'Account created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    public function listView(){
        return view('account/account-list');
    }

    public function list(Request $request){
        $datas = ChartOfAccount::where('subscriber_id', Auth::user()->subscriber_id)->get();
        foreach($datas as $data){
            $x = Transaction::where([
                ['subscriber_id', Auth::user()->subscriber_id],
                ['head_code', $data->head_code]
            ])->latest()->first();

            // $x = ChartOfAccount::rightjoin('transactions', 'chart_of_accounts.head_code', 'transactions.head_code')
            // ->where([
            //     ['chart_of_accounts.subscriber_id', Auth::user()->subscriber_id],
            //     ['transactions.head_code', $data->head_code]
            // ])
            // ->latest('transactions.created_at')
            // ->first();

            if($x){
                $y[]=$x;
            }

        }

        // $data = ChartOfAccount::join('transactions', 'chart_of_accounts.head_code', 'transactions.head_code')
        // ->where([['chart_of_accounts.subscriber_id', Auth::user()->subscriber_id]])->latest('transactions.created_at')
        // ->first();

        // Log::info(json_encode($y));
        // Log::info($data);

        if($request -> ajax()){
            return response()->json([
                'data'=>$y,
                'message'=>'Success'
            ]);
        }
    }
}
