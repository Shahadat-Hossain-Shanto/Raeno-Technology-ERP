<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DuePayment;
use App\Models\Deposit;
use App\Models\Client;
use App\Models\User;

use DB;

class DepositAPIController extends Controller
{
    public function store(Request $request){
        $deposit = new Deposit;

        $users = User::where('contact_number', $request->userId)
                    ->orWhere('email', $request->userId)
                    ->get();

        foreach($users as $user){
            $userName =  $user->name;
        }

        $depositDate                = strtotime($request->depositDate);
        
        $deposit->deposit_date      = date('Y-m-d', $depositDate);
        $deposit->due               = 0;
        $deposit->deposit           = $request->deposit;

        if($request->type == "customer"){
            if((int)$request->id != 0){
                // $client = Client::find((int)$request->clientId);
                $dueLists = DuePayment::where([
                    ['clientId', (int)$request->id],
                    ['subscriber_id', (int)$request->subscriberId],
                    ['store_id', (int)$request->storeId]
                ])
                ->get();

                $totalDue = 0;

                foreach($dueLists as $dueList){

                    $totalDue = $totalDue + $dueList->due_amount;
                    
                }
            }else{
                return response() -> json([
                    'status'=>200,
                    'message' => 'Customer not exist!'
                ]); 
            }

            $lastBalance = DB::table('deposits')->where('client_id', (int)$request->id)->latest()->first();

            if($lastBalance){
                $deposit->balance           = $lastBalance->balance - $request->deposit;
            }else{
                $deposit->balance = $request->deposit;
            }

            
            
            $deposit->status            = 'deposit';
            $deposit->deposit_type      = $request->deposit_type;
            $deposit->note              = $request->note;

            $deposit->image             = $request->image;
            $deposit->client_id         = (int)$request->id;
            $deposit->store_id          = (int)$request->storeId;
            $deposit->salesBy_id        = (int)$request->userId;
            $deposit->salesBy_name      = $userName;
            $deposit->subscriber_id     = (int)$request->subscriberId;
            
            $deposit->save();
        }else{
            if((int)$request->id != 0){
                // $client = Client::find((int)$request->clientId);
                $dueLists = DuePayment::where([
                    ['supplierId', (int)$request->id],
                    ['subscriber_id', (int)$request->subscriberId],
                    ['store_id', (int)$request->storeId]
                ])
                ->get();

                $totalDue = 0;

                foreach($dueLists as $dueList){

                    $totalDue = $totalDue + $dueList->due_amount;
                    
                }
            }else{
                return response() -> json([
                    'status'=>200,
                    'message' => 'Supplier not exist!'
                ]); 
            }

            $lastBalance = DB::table('deposits')->where('supplier_id', (int)$request->id)->latest()->first();

            if($lastBalance){
                $deposit->balance           = $lastBalance->balance - $request->deposit;
            }else{
                $deposit->balance = $request->deposit;
            }

            
            
            $deposit->status            = 'deposit';
            $deposit->deposit_type      = $request->deposit_type;
            $deposit->note              = $request->note;

            $deposit->image             = $request->image;
            $deposit->supplier_id       = (int)$request->id;
            $deposit->store_id          = (int)$request->storeId;
            $deposit->salesBy_id        = (int)$request->userId;
            $deposit->salesBy_name      = $userName;
            $deposit->subscriber_id     = (int)$request->subscriberId;
            
            $deposit->save();
        }
        

        $depositId = $deposit->id;

        return response() -> json([
            'status'=>200,
            'message' => 'Deposit added Successfully!'
        ]); 
    }

    public function getDepositId(){
        $depositId = DB::table('deposits')->latest('id')->first();
        return $depositId->id;
    }

    
}
