<?php

namespace App\Http\Controllers;

use Log;
use Session;
use Carbon\Carbon;
use App\Models\Price;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use App\Models\ProductInHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StockInAPIController extends Controller
{
    public function productIn(Request $request,$subscriberId,$storeId){
        Session::put('subscriberId', $subscriberId);
        foreach($request->productList as $product){
            if($product['serialNumbers']!=null){
                foreach($product['serialNumbers'] AS $serialNumber)
                {
                    $serial = ProductSerial::where([
                        ['productId', $product['productId']],
                        ['variantId', $product['variantId']],
                        ['serialNumber', $serialNumber['serialNumber']]
                    ])->get();
                    foreach($serial as $serialNumber){
                        $number = $serialNumber->serialNumber;
                    }
                    if($serial->isNotEmpty()){
                        return response() -> json([
                            'status'=>400,
                            'productName'=>$product['product'],
                            'serialNumber'=>$number,
                            'error' => 'Serial Number Exists'
                        ]);
                    }
                }
            }
        }
        foreach($request->productList as $product){
            $storeInventory = StoreInventory::where([
                                ['store_id', '=', $storeId],
                                ['productId', '=', $product['productId']],
                                ['variant_id', '=', (int)$product['variantId']]
                            ])->get();

            if($storeInventory->isEmpty()){

                $newStore = new StoreInventory;

                $newStore->onHand = $product['quantity'];
                $newStore->productIncoming = $product['quantity'];
                $newStore->mrp = $product['mrp'];
                $newStore->price = $product['unitPrice'];
                $newStore->productId = $product['productId'];
                $newStore->store_id = $storeId;
                $newStore->variant_id = $product['variantId'];
                $newStore->variant_name = $product['variant'];
                $newStore->save();

                $history = new ProductInHistory;
                $history->store = $storeId;
                $history->store_name = $product['store'];
                $history->product = $product['productId'];
                $history->product_name = $product['product'];
                $history->quantity = $product['quantity'];
                $history->unit_price = $product['unitPrice'];
                $history->mrp = $product['mrp'];
                $history->subscriber_id = $subscriberId;
                // $history->user_id = $id;
                $history->product_in_num = $product['productInNum'];
                $history->variant_id = $product['variantId'];
                $history->variant_name = $product['variant'];
                $history->save();

                $price = new Price;
                $price->product_id = $product['productId'];
                $price->price = $product['unitPrice'];
                $price->mrp = $product['mrp'];
                $price->quantity = $product['quantity'];
                $price->store_id = $storeId;
                $price->subscriber_id = $subscriberId;
                $price->product_in_num = $product['productInNum'];
                $price->variant_id = $product['variantId'];
                $price->variant_name = $product['variant'];
                $price->save();

            }else{
                foreach($storeInventory as $inventory){
                    $inventoryId = $inventory->id;
                }

                $store = StoreInventory::find($inventoryId);

                $onHand = $store->onHand;
                $productIncoming = $store->productIncoming;
                $mrp = $store->mrp;
                $price = $store->price;

                $store->onHand = $product['quantity'] + $onHand;
                $store->productIncoming = $product['quantity'] + $productIncoming;
                $store->mrp = $product['mrp'];
                $store->price = $product['unitPrice'];
                $store->save();

                $history = new ProductInHistory;
                $history->store = $storeId;
                $history->store_name = $product['store'];
                $history->product = $product['productId'];
                $history->product_name = $product['product'];
                $history->quantity = $product['quantity'];
                $history->unit_price = $product['unitPrice'];
                $history->mrp = $product['mrp'];
                $history->subscriber_id =  $subscriberId;
                // $history->user_id = $id;
                $history->product_in_num = $product['productInNum'];
                $history->variant_id = $product['variantId'];
                $history->variant_name = $product['variant'];

                $history->save();

                if( $mrp != $product['mrp'] || $price != $product['unitPrice'] ){

                    $price = new Price;
                    $price->product_id = $product['productId'];
                    $price->price = $product['unitPrice'];
                    $price->mrp = $product['mrp'];
                    $price->quantity = $product['quantity'];
                    $price->store_id = $storeId;
                    $price->subscriber_id = $subscriberId;
                    $price->product_in_num = $product['productInNum'];
                    $price->variant_id = $product['variantId'];
                    $price->variant_name = $product['variant'];
                    $price->save();
                }
            }

            if($product['serialNumbers']!=null){
                foreach($product['serialNumbers'] AS $serialNumber)
                {
                    $productSerial = new ProductSerial;
                    $productSerial->productId       = doubleval($product['productId']);
                    $productSerial->productName     = $product['product'];
                    $productSerial->variantId       = doubleval($product['variantId']);
                    $productSerial->variantName     = $product['variant'];
                    $productSerial->storeId         = $storeId;
                    $productSerial->serialNumber    = $serialNumber['serialNumber'];
                    $productSerial->purchaseId      =   'Product In';
                    $todayDate = Carbon::today();
                    $productSerial->purchaseDate    = $todayDate;
                    $productSerial->created_by      = $subscriberId;
                    $productSerial->subscriber_id   = $subscriberId;

                    $productSerial->save();
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Product-In successfully!',
        ]);

    }
}
