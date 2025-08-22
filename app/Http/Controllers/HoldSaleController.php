<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\User;

use App\Models\HoldSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HoldSaleController extends Controller
{
    public function store(Request $request){
        // log::info($request);

        $users = User::where('contact_number', $request->salesBy)
                    ->orWhere('email', $request->salesBy)
                    ->get();

        foreach($users as $user){
            $userId =  $user->id;
            $userName = $user->name;
        }

        //Check if reference already exists
        $checkReferences = HoldSale::where('reference', $request->reference)->get();
        // Log::info($checkReferences);

        //if not exists then insert hold
        if($checkReferences->isEmpty()){

            foreach($request->orderDetails as $orderDetail){
                $holdSale = new HoldSale;

                $holdSale->reference                    = $request->reference;

                if($request->clientId == ""){
                    $clientId = 0;
                }else{
                    $clientId = (int)$request->clientId;
                }

                if($request->clientName == "" || $request->clientName == "N/A"){
                    $clientName = 0;
                }else{
                    $clientName = $request->clientName;
                }

                if($request->clientMobile == "" || $request->clientMobile == "N/A"){
                    $clientMobile = 0;
                }else{
                    $clientMobile = $request->clientMobile;
                }

                $holdSale->client_id                    = $clientId;
                $holdSale->client_name                  = $clientName;
                $holdSale->client_mobile                = $clientMobile;
                $holdSale->subscriber_id                = (int)$request->subscriberId;
                $holdSale->pos_id                       = (int)$request->posId;
                $holdSale->store_id                     = (int)$request->storeId;
                $holdSale->user_id                      = (int)$userId;

                $orderDateStr                           = strtotime($request->orderDate);
                $holdSale->orderDate                    = date('Y-m-d', $orderDateStr);


                $holdSale->productId                    = (int)$orderDetail['productId'];
                $holdSale->productName                  = $orderDetail['productName'];
                $holdSale->mrp                          = $orderDetail['mrp'];
                $holdSale->quantity                     = $orderDetail['quantity'];
                $holdSale->type                         = $orderDetail['type'];
                $holdSale->product_serial               = $orderDetail['productSerial'];
                $holdSale->totalPrice                   = $orderDetail['totalPrice'];
                $holdSale->totalDiscount                = $orderDetail['totalDiscount'];
                $holdSale->totalTax                     = $orderDetail['totalTax'];
                $holdSale->availableOffer               = $orderDetail['available_offer'];
                $holdSale->requiredQuantity             = $orderDetail['requiredQuantity'];
                $holdSale->grandTotal                   = $orderDetail['grandTotal'];
                $holdSale->discount                     = $orderDetail['discount'];
                $holdSale->specialDiscount              = $orderDetail['specialDiscount'];
                $holdSale->offerItemId                  = $orderDetail['offerItemId'];
                $holdSale->offerName                    = $orderDetail['offerName'];
                $holdSale->offerQuantity                = $orderDetail['offerQuantity'];
                $holdSale->tax                          = $orderDetail['tax'];
                $holdSale->productQty                   = $orderDetail['productQty'];
                $holdSale->freeItemQty                  = $orderDetail['freeItemQty'];
                $holdSale->variant_id                   = (int)$orderDetail['variant_id'];

                $holdSale->save();
            }

            return response() -> json([
                'message' => 'New inserted'
            ]);

        }else{ //else delete existing and then insert

            foreach($checkReferences as $checkReference){
                $findReference = HoldSale::find($checkReference->id)->delete($checkReference->id);
            }

            foreach($request->orderDetails as $orderDetail){
                $holdSale = new HoldSale;

                $holdSale->reference                    = $request->reference;

                if($request->clientId == ""){
                    $clientId = 0;
                }else{
                    $clientId = (int)$request->clientId;
                }

                if($request->clientName == "" || $request->clientName == "N/A"){
                    $clientName = 0;
                }else{
                    $clientName = $request->clientName;
                }

                if($request->clientMobile == "" || $request->clientMobile == "N/A"){
                    $clientMobile = 0;
                }else{
                    $clientMobile = $request->clientMobile;
                }

                $holdSale->client_id                    = $clientId;
                $holdSale->client_name                  = $clientName;
                $holdSale->client_mobile                = $clientMobile;
                $holdSale->subscriber_id                = (int)$request->subscriberId;
                $holdSale->pos_id                       = (int)$request->posId;
                $holdSale->store_id                     = (int)$request->storeId;
                $holdSale->user_id                      = (int)$userId;

                $orderDateStr                           = strtotime($request->orderDate);
                $holdSale->orderDate                    = date('Y-m-d', $orderDateStr);


                $holdSale->productId                    = (int)$orderDetail['productId'];
                $holdSale->productName                  = $orderDetail['productName'];
                $holdSale->mrp                          = $orderDetail['mrp'];
                $holdSale->quantity                     = $orderDetail['quantity'];
                $holdSale->totalPrice                   = $orderDetail['totalPrice'];
                $holdSale->totalDiscount                = $orderDetail['totalDiscount'];
                $holdSale->totalTax                     = $orderDetail['totalTax'];
                $holdSale->availableOffer               = $orderDetail['available_offer'];
                $holdSale->requiredQuantity             = $orderDetail['requiredQuantity'];
                $holdSale->grandTotal                   = $orderDetail['grandTotal'];
                $holdSale->discount                     = $orderDetail['discount'];
                $holdSale->specialDiscount              = $orderDetail['specialDiscount'];
                $holdSale->offerItemId                  = $orderDetail['offerItemId'];
                $holdSale->offerName                    = $orderDetail['offerName'];
                $holdSale->offerQuantity                = $orderDetail['offerQuantity'];
                $holdSale->tax                          = $orderDetail['tax'];
                $holdSale->productQty                   = $orderDetail['productQty'];
                $holdSale->freeItemQty                  = $orderDetail['freeItemQty'];
                $holdSale->variant_id                   = (int)$orderDetail['variant_id'];

                $holdSale->save();
            }

            return response() -> json([
                'message' => 'Delete and inserted'
            ]);
        }

    }

    public function list(Request $request){

        $sessionStoreId = $request->session()->get('storeId');

        $holds = HoldSale::where('store_id',  $sessionStoreId)->get()->unique('reference');

        if($request -> ajax()){
            return response()->json([
                'holds'=>$holds,
            ]);
        }
    }

    public function edit(Request $request, $referenceId){
        $holds = HoldSale::where('reference', $referenceId)->get();
        // Log::info($holds);
        if($holds){
            return response()->json([
                'status'=> 200,
                'message'=>'Success',
                'holds'=>$holds,
            ]);
        }
    }

    public function delete(Request $request, $referenceId){
        $checkReferences = HoldSale::where('reference', $referenceId)->get();
        foreach($checkReferences as $checkReference){
                $findReference = HoldSale::find($checkReference->id)->delete($checkReference->id);
        }
        return response()->json([
            'message'=>'Success',
        ]);
    }
}
