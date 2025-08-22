<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductTransferHistory;



class ProductTransferController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $products = Product::join('inventories', 'products.id', 'inventories.productId')->where('products.subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();
        return view('product/product-transfer', ['stores' => $stores, 'products' => $products, 'variants' => $variants]);
    }

    public function productTransfer(Request $request){

        foreach($request->productList as $product){

            $fromStoreId = $product['fromStoreId'];
            $toStoreId = $product['toStoreId'];

            if($fromStoreId == "inventory"){

                //FROM INVENTORY
                $productId = (int)$product['productId'];
                $variantId = (int)$product['variantId'];

                $inventoryX = Inventory::where([
                    ['productId', $productId],
                    ['variant_id', $variantId]
                ])->first();

                $inventory = Inventory::find($inventoryX->id);

                $fromStoreMrp = $inventory->mrp;
                $fromStorePrice = $inventory->price;
                $fromStoreSafety_stock = $inventory->safety_stock;

                $inventoryOnHand = $inventory->onHand;
                $productOutgoing = $inventory->productOutgoing;
                $check = $inventoryOnHand - doubleval($product['quantity']);
                $variant = $inventory->variant_id;

                if($check >= 0 ){
                    $inventory->onHand = $inventoryOnHand - doubleval($product['quantity']);
                    $inventory->productOutgoing = $productOutgoing + doubleval($product['quantity']);
                }else{
                    return response()->json([
                        'status' => 200,
                        'message' => 'Inventory low! Please check your inventory then try again.',
                    ]);
                }

                // $inventory->save();

                //TO STORE OR INVENTORY
                if($toStoreId == "inventory"){
                    $toProductId = (int)$product['productId'];

                    // $toInventory = Inventory::find($toProductId);
                    $inventoryY = Inventory::where([
                        ['productId', $productId],
                        ['variant_id', $variantId]
                    ])->first();
                    $toInventory = Inventory::find($inventoryY->id);


                    $toInventoryOnHand = $toInventory->onHand;
                    $toProductIncoming = $toInventory->productIncoming;

                    $toInventory->onHand = $toInventoryOnHand + doubleval($product['quantity']);
                    $toInventory->productIncoming = $toProductIncoming + doubleval($product['quantity']);
                    $toInventory->save();
                    $inventory->save();

                }else{
                    $toStores = StoreInventory::where([
                            ['store_id', '=', (int)$product['toStoreId']],
                            ['productId', '=', (int)$product['productId'] ],
                            ['variant_id', '=', $variantId ]
                        ])->get();

                    if($toStores->isEmpty()){

                        $newStoreInventory = new StoreInventory;

                        $newStoreInventory->onHand = doubleval($product['quantity']);
                        $newStoreInventory->productIncoming = doubleval($product['quantity']);
                        $newStoreInventory->safety_stock = $fromStoreSafety_stock;
                        $newStoreInventory->mrp = $fromStoreMrp;
                        // $newStoreInventory->measuringType = $fromStoreMeasuringType;
                        $newStoreInventory->price = $fromStorePrice;
                        $newStoreInventory->productId = (int)$product['productId'];
                        $newStoreInventory->store_id = (int)$product['toStoreId'];
                        $newStoreInventory->variant_id = (int)$product['variantId'];
                        $newStoreInventory->variant_name = $product['variant'];

                        $history = new ProductTransferHistory;

                        $history->from_store        = $product['fromStoreId'];
                        $history->to_store          = $product['toStoreId'];
                        $history->product           = $product['productId'];
                        $history->quantity          = $product['quantity'];
                        $history->subscriber_id     = Auth::user()->subscriber_id;
                        $history->user_id           = Auth::user()->id;
                        $history->variant_id = (int)$product['variantId'];
                        $history->variant_name = $product['variant'];


                        $price = new Price;
                        $price->product_id = $product['productId'];
                        $price->price = $fromStorePrice;
                        $price->mrp = $fromStoreMrp;
                        $price->quantity = doubleval($product['quantity']);
                        $price->subscriber_id = Auth::user()->subscriber_id;
                        $price->store_id = $product['toStoreId'];
                        $price->variant_id = $product['variantId'];
                        $price->variant_name = $product['variant'];
                        $price->product_in_num = 0;

                        $price->save();

                        $history->save();
                        $newStoreInventory->save();
                        $inventory->save();

                        // return response()->json([
                        //     'status' => 200,
                        //     'message' => 'The product is transferd and added to the store '.$product['toStore'],
                        // ]);

                    }else{
                        foreach($toStores as $toStore){
                            $toStoreId = $toStore->id;
                        }

                        $toStoreFind = StoreInventory::find($toStoreId);

                        $toStoreOnHand = $toStoreFind->onHand;
                        // Log::info($toStoreOnHand);
                        $toProductIncoming = $toStoreFind->productIncoming;

                        $toStoreFind->onHand =  $toStoreFind->onHand + doubleval($product['quantity']);
                        $toStoreFind->productIncoming = $toProductIncoming + doubleval($product['quantity']);

                        //-----------------------------------------------------------
                        $history = new ProductTransferHistory;

                        $history->from_store        = $product['fromStoreId'];
                        $history->to_store          = $product['toStoreId'];
                        $history->product           = $product['productId'];
                        $history->quantity          = $product['quantity'];
                        $history->subscriber_id     = Auth::user()->subscriber_id;
                        $history->user_id           = Auth::user()->id;
                        $history->variant_id = (int)$product['variantId'];
                        $history->variant_name = $product['variant'];

                        $history->save();
                        $toStoreFind->save();
                        $inventory->save();
                    }

                }

            }else{

                //FROM STORE
                $variantId = (int)$product['variantId'];
                $fromStores = StoreInventory::where([
                            ['store_id', '=', (int)$product['fromStoreId']],
                            ['productId', '=', (int)$product['productId']],
                            ['variant_id', '=', $variantId ]
                        ])->get();

                if($fromStores->isEmpty()){
                    return response()->json([
                        'status' => 200,
                        'message' => 'No product in '.$product['fromStore'].'! Please add the product in your store to transfer.',
                    ]);
                }else{

                    foreach($fromStores as $fromStore){
                        $fromStoreId = $fromStore->id;
                    }

                    $fromStoreFind = StoreInventory::find($fromStoreId);

                    $fromStoreOnhand = $fromStoreFind->onHand;
                    $fromProductOutgoing = $fromStoreFind->productOutgoing;

                    // Log::info($fromStoreOnhand);

                    if($fromStoreOnhand == 0 || $fromStoreOnhand < doubleval($product['quantity'])){
                        return response()->json([
                            'status' => 200,
                            'message' => 'Inventory low! Please check your inventory then try again.',
                        ]);
                    }else{
                        $fromStoreMrp           = $fromStoreFind->mrp;
                        $fromStoreSafety_stock  = $fromStoreFind->safety_stock;
                        $fromStorePrice         = $fromStoreFind->price;

                        $fromStoreFind->onHand =  $fromStoreOnhand - doubleval($product['quantity']);
                        $fromStoreFind->productOutgoing =  $fromProductOutgoing + doubleval($product['quantity']);

                        // $fromStoreFind->save();


                        //TO STORE OR INVENTORY
                        if($toStoreId == "inventory"){
                            $toProductId = (int)$product['productId'];


                            // $toInventory = Inventory::find($toProductId);
                            $toInventoryX = Inventory::where([
                                ['productId', '=', (int)$product['productId']],
                                ['variant_id', '=', $variantId ]
                            ])->first();

                            // Log::info($toInventoryX);
                            if($toInventoryX){
                                $toInventory = Inventory::find($toInventoryX->id);
                                $toInventoryOnHand = $toInventory->onHand;
                                $toIncoming = $toInventory->productIncoming;
                                $toInventory->onHand = $toInventoryOnHand + doubleval($product['quantity']);
                                $toInventory->productIncoming = $toIncoming + doubleval($product['quantity']);

                                $fromStoreFind->save();
                                $toInventory->save();
                            }else{
                                $inventory = new Inventory;
                                $inventory->onHand = doubleval($product['quantity']);
                                $inventory->productIncoming = doubleval($product['quantity']);
                                $inventory->productOutgoing = 0;
                                $inventory->mrp = $fromStoreMrp;
                                $inventory->price = $fromStorePrice;
                                $inventory->productId = (int)$product['productId'];
                                $inventory->variant_id = (int)$product['variantId'];
                                $inventory->variant_name = $product['variant'];
                                $inventory->safety_stock = $fromStoreSafety_stock;
                                $inventory->subscriber_id = Auth::user()->subscriber_id;

                                $history = new ProductTransferHistory;

                                $history->from_store        = $product['fromStoreId'];
                                $history->to_store          = $product['toStoreId'];
                                $history->product           = $product['productId'];
                                $history->quantity          = $product['quantity'];
                                $history->subscriber_id     = Auth::user()->subscriber_id;
                                $history->user_id           = Auth::user()->id;
                                $history->variant_id        = (int)$product['variantId'];
                                $history->variant_name      = $product['variant'];

                                $price = new Price;
                                $price->product_id = $product['productId'];
                                $price->price = $fromStorePrice;
                                $price->mrp = $fromStoreMrp;
                                $price->quantity = doubleval($product['quantity']);
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->store_id = 0;
                                $price->variant_id = $product['variantId'];
                                $price->variant_name = $product['variant'];
                                $price->product_in_num = 0;

                                $price->save();

                                $history->save();
                                $fromStoreFind->save();
                                $inventory->save();
                            }

                        }else{
                            $toStores = StoreInventory::where([
                                    ['store_id', '=', (int)$product['toStoreId']],
                                    ['productId', '=', (int)$product['productId'] ],
                                    ['variant_id', '=', $variantId ]
                                ])->get();

                            if($toStores->isEmpty()){
                                // Log::info('Empty');

                                $newStoreInventory = new StoreInventory;

                                $newStoreInventory->onHand = doubleval($product['quantity']);
                                $newStoreInventory->productIncoming = doubleval($product['quantity']);
                                $newStoreInventory->safety_stock = $fromStoreSafety_stock;
                                $newStoreInventory->mrp = $fromStoreMrp;
                                // $newStoreInventory->measuringType = $fromStoreMeasuringType;
                                $newStoreInventory->price = $fromStorePrice;
                                $newStoreInventory->productId = (int)$product['productId'];
                                $newStoreInventory->store_id = (int)$product['toStoreId'];
                                $newStoreInventory->variant_id = (int)$product['variantId'];
                                $newStoreInventory->variant_name = $product['variant'];



                                $history = new ProductTransferHistory;

                                $history->from_store        = $product['fromStoreId'];
                                $history->to_store          = $product['toStoreId'];
                                $history->product           = $product['productId'];
                                $history->quantity          = $product['quantity'];
                                $history->subscriber_id     = Auth::user()->subscriber_id;
                                $history->user_id           = Auth::user()->id;
                                $history->variant_id        = (int)$product['variantId'];
                                $history->variant_name      = $product['variant'];


                                $price = new Price;
                                $price->product_id = $product['productId'];
                                $price->price = $fromStorePrice;
                                $price->mrp = $fromStoreMrp;
                                $price->quantity = doubleval($product['quantity']);
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->store_id = $product['toStoreId'];
                                $price->variant_id = $product['variantId'];
                                $price->variant_name = $product['variant'];
                                $price->product_in_num = 0;

                                $price->save();

                                $history->save();
                                $fromStoreFind->save();
                                $newStoreInventory->save();

                                // return response()->json([
                                //     'status' => 200,
                                //     'message' => 'Product Transfered and added successfully!',
                                // ]);

                            }else{
                                // Log::info('Not empty');

                                foreach($toStores as $toStore){
                                    $toStoreId = $toStore->id;
                                }

                                $toStoreFind = StoreInventory::find($toStoreId);
                                $toStoreOnhand = $toStoreFind->onHand;
                                $toProductIncomingX = $toStoreFind->productIncoming;
                                $toStoreFind->onHand =  $toStoreOnhand + doubleval($product['quantity']);
                                $toStoreFind->productIncoming =  $toProductIncomingX + doubleval($product['quantity']);



                                $history = new ProductTransferHistory;

                                $history->from_store        = $product['fromStoreId'];
                                $history->to_store          = $product['toStoreId'];
                                $history->product           = $product['productId'];
                                $history->quantity          = $product['quantity'];
                                $history->subscriber_id     = Auth::user()->subscriber_id;
                                $history->user_id           = Auth::user()->id;
                                $history->variant_id        = (int)$product['variantId'];
                                $history->variant_name      = $product['variant'];

                                $fromStoreFind->save();
                                $history->save();
                                $toStoreFind->save();
                            }
                        }
                    }
                }
            }

            $productType = Product::find($product['productId']);
            if($productType->barcode==''){
                $barcode='N/A';
            }
            else{
                $barcode=$productType->barcode;
            }
            if($productType->type!='Serialize'){
                $productSerials = ProductSerial::where([
                        ['storeId', $product['toStoreId']],
                        ['productId', $product['productId']],
                        ['variantId', $product['variantId']],
                        // ['serialNumber', $productType->barcode]
                    ])->get();

                if($productSerials->isEmpty()){
                    $productSerial = new ProductSerial;
                    $productSerial->productId = doubleval($product['productId']);
                    $productSerial->productName = $product['product'];
                    $productSerial->variantId = doubleval($product['variantId']);
                    $productSerial->variantName = $product['variant'];
                    $productSerial->storeId = $product['toStoreId'];
                    $productSerial->serialNumber = $barcode;

                    $todayDate = Carbon::today();
                    $productSerial->purchaseDate  = $todayDate;
                    $productSerial->created_by = Auth::user()->name;
                    $productSerial->subscriber_id =  Auth::user()->subscriber_id;
                    $productSerial->save();
                }
                else{
                    foreach($productSerials AS $serial){
                        $productSerialId=$serial->id;
                    }
                    $productSerial=ProductSerial::find($productSerialId);
                    if($barcode!='N/A' || $productSerial->serialNumber==''){
                        $productSerial->serialNumber = $barcode;
                        $productSerial->save();
                    }
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Product Transfered successfully!',
        ]);

    }

    public function product($id, $variantId, $storeId){
        if($storeId=='inventory'){
            $productQuantity = Inventory::where([
                ['productId', $id],
                ['variant_id', $variantId]
            ])->first();
        }
        else{
            $productQuantity = StoreInventory::where([
                ['store_id', '=', $storeId],
                ['productId', '=', $id ],
                ['variant_id', '=', $variantId ]
            ])->first();
        }

        $product = Product::find($id);
        return response()->json([
            'status'=>200,
            'productType'=>$product->type,
            'productQuantity'=>$productQuantity->onHand,
            'barcode'=>$product->barcode
        ]);
    }
    public function productSerial(Request $request){
        // log::info($request);

        if($request->FromStoreId=='inventory')
        {
            $FromStoreId=0;
        }
        else{
            $FromStoreId=$request->FromStoreId;
        }
        if($request->ToStoreId=='inventory')
        {
            $ToStoreId=0;
        }
        else{
            $ToStoreId=$request->ToStoreId;
        }
        $id=0;
        foreach($request->serialNumbers AS $serialNumber)
        {
            $serial = ProductSerial::where([
                ['productId', $request->ProductId],
                ['variantId', $request->VariantId],
                ['storeId', $FromStoreId],
                ['serialNumber', $serialNumber['serialNumber']],
                ['saleId', 0]
            ])->get();

            $storeProductSerial = ProductSerial::where([
                ['productId', $request->ProductId],
                ['variantId', $request->VariantId],
                ['storeId', $ToStoreId],
                ['serialNumber', $serialNumber['serialNumber']],
                ['saleId', 0]
            ])->get();

            if($serial->isEmpty()){
                return response() -> json([
                    'status'=>400,
                    'serialNumber'=>$serialNumber['serialNumber'],
                    'error' => 'Serial Number dose not Exists'
                ]);
            }
            else if($storeProductSerial->isNotEmpty()){
                return response() -> json([
                    'status'=>400,
                    'serialNumber'=>$serialNumber['serialNumber'],
                    'error' => 'Serial Number already Exists in Store'
                ]);
            }
            else{
                foreach($serial as $serialId){
                    $id = $serialId->id;
                }
                $serial = ProductSerial::find($id);
                $serial->storeId = $ToStoreId;
                $serial->updated_by = Auth::user()->name;
                $serial->save();
            }
        }
        return response() -> json([
            'status'=>200,
            'message' => 'Serial Number updated Successfully'
        ]);
    }
}

