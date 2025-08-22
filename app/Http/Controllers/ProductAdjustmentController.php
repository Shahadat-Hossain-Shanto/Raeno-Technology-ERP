<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Store;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAdjustmentHistory;

class ProductAdjustmentController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('product/product-adjustment', ['stores' => $stores]);
    }

    public function onHand(Request $request, $id, $variantId, $storeId){
        // log::alert($request);
        if($storeId=='inventory'||$storeId=='0'){
            $onHand = DB::table("products")
                ->join('inventories', 'products.id', '=', 'inventories.productId')
                ->select("onHand")
                ->where([
                    ['inventories.productId', $id],
                    ['inventories.variant_id', $variantId]
                ])
                ->first();
        }
        else
        {
            $onHand = DB::table("products")
                ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                ->select("onHand")
                ->where([
                    ['store_inventories.productId', $id],
                    ['store_inventories.variant_id', $variantId],
                    ['store_inventories.store_id', $storeId]

                ])
                ->first();
        }
        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'onHand' => $onHand->onHand
            ]);
        }
    }

    public function productAdjustment(Request $request){
        log::alert($request);

        foreach($request->productList AS $serialNumber)
        {
            if($serialNumber['Type']=='Serialize' && $serialNumber['number']!='Serial number added'){
                // log::alert($serialNumber['number']);
                $serial = ProductSerial::where([
                    ['productId', $serialNumber['productId']],
                    ['variantId', $serialNumber['variantId']],
                    ['storeId', $serialNumber['storeId']],
                    ['serialNumber', $serialNumber['number']],
                    ['saleId', 0]
                ])->get();

                if($serial->isEmpty()){
                    return response() -> json([
                        'status'=>400,
                        'message' => 'Serial Number Dose not Exists'
                    ]);
                }
            }
        }

        foreach($request->productList as $product){
            if($product['storeId'] != 0){
                $storeInventory = StoreInventory::where([
                    ['store_id', '=', $product['storeId']],
                    ['productId', '=', $product['productId']],
                    ['variant_id', '=', (int)$product['variantId']]
                ])->get();

                // Log::info($product);
                // Log::info($storeInventory->isEmpty());

                if($storeInventory->isEmpty()){
                    return response()->json([
                        'status' => 400,
                        'message' => 'You have to add the product in the Store first.',
                    ]);
                }else{
                    foreach($storeInventory as $storeAdjustment){
                        $storeId = $storeAdjustment->id;
                    }

                    $store = StoreInventory::find($storeId);

                    $onHand = $store->onHand;
                    $productIncoming = $store->productIncoming;

                    if($onHand>=$product['quantity'])
                    {
                        $store->onHand = $onHand-$product['quantity'];
                        $store->productIncoming = $productIncoming-$product['quantity'];
                        $store->save();
                    }
                    else{
                        return response()->json([
                            'status' => 400,
                            'message' => 'Stock Empty',
                        ]);
                    }

                    $history = new ProductAdjustmentHistory;
                    $history->store = $product['storeId'];
                    $history->store_name = $product['store'];
                    $history->product = $product['productId'];
                    $history->product_name = $product['product'];
                    $history->variant_id = $product['variantId'];
                    $history->variant_name = $product['variant'];
                    $history->quantity = $product['quantity'];
                    $history->product_adjustment_num = $product['productOutNum'];
                    $history->date = $product['date'];
                    $history->user_id = Auth::user()->id;
                    $history->subscriber_id = Auth::user()->subscriber_id;
                    $history->save();
                }

            }else{
                $inventorises = Inventory::where([
                                ['productId', '=', $product['productId']],
                                ['variant_id', '=', (int)$product['variantId']],
                                ['subscriber_id', '=', Auth::user()->subscriber_id]
                            ])->get();

                if($inventorises->isEmpty()){
                    return response()->json([
                        'status' => 400,
                        'message' => 'You have to add the product in the Warehouse first.',
                    ]);
                }
                else{
                    foreach($inventorises as $inventory){
                        $inventoryId = $inventory->id;
                    }

                    $inventory = Inventory::find($inventoryId);

                    $onHand          = $inventory->onHand;
                    $productIncoming = $inventory->productIncoming;

                    if($onHand>=$product['quantity'])
                    {
                        $inventory->onHand = $onHand-$product['quantity'];
                        $inventory->productIncoming = $productIncoming-$product['quantity'];
                        $inventory->save();
                    }
                    else{
                        return response()->json([
                            'status' => 400,
                            'message' => 'Stock Empty',
                        ]);
                    }

                    $history = new ProductAdjustmentHistory;
                    $history->store = $product['storeId'];
                    $history->store_name = "Inventory";
                    $history->product = $product['productId'];
                    $history->product_name = $product['product'];
                    $history->variant_id = $product['variantId'];
                    $history->variant_name = $product['variant'];
                    $history->quantity = $product['quantity'];
                    $history->product_adjustment_num = $product['productOutNum'];
                    $history->date = $product['date'];
                    $history->user_id = Auth::user()->id;
                    $history->subscriber_id = Auth::user()->subscriber_id;
                    $history->save();
                }
            }

            if($serialNumber['Type']=='Serialize' && $serialNumber['number']!='Serial number added'){
                $serial = ProductSerial::where([
                    ['productId', $product['productId']],
                    ['variantId', $product['variantId']],
                    ['storeId', $product['storeId']],
                    ['serialNumber', $product['number']],
                    ['saleId', 0]
                ])->first()->delete();
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Product Adjusted Successfully!',
        ]);
    }

    public function productSerialDelete(Request $request){
        foreach($request->serialNumbers AS $serialNumber)
        {
            $serial = ProductSerial::where([
                ['productId', $request->ProductId],
                ['variantId', $request->VariantId],
                ['storeId', $request->StoreId],
                ['serialNumber', $serialNumber['serialNumber']],
                ['saleId', 0]
            ])->get();

            if($serial->isEmpty()){
                return response() -> json([
                    'status'=>400,
                    'serialNumber'=>$serialNumber['serialNumber'],
                    'error' => 'Serial Number Dose not Exists'
                ]);
            }
        }

        foreach($request->serialNumbers AS $serialNumber)
        {
            $serial = ProductSerial::where([
                ['productId', $request->ProductId],
                ['variantId', $request->VariantId],
                ['storeId', $request->StoreId],
                ['serialNumber', $serialNumber['serialNumber']],
                ['saleId', 0]
            ])->first()->delete();
        }
        return response() -> json([
            'status'=>200,
            'message' => 'Serial Number Adjusted Successfully'
        ]);
    }
}
