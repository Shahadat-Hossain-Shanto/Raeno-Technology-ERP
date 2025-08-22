<?php

namespace App\Http\Controllers;

// use Log;
use App\Models\Leaf;
use App\Models\Batch;
use App\Models\Price;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use Illuminate\Support\Carbon;
use App\Models\ProductInHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ProductInController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $products = Product::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $products = Product::where('products.subscriber_id', Auth::user()->subscriber_id)->get();
        $batches = Batch::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $leaves = Leaf::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();

        return view('product/product-in', ['stores' => $stores, 'products' => $products, 'batches' => $batches, 'leaves' => $leaves, 'variants' => $variants]);
    }

    public function productIn(Request $request){
        // log::alert($request);
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

                    $storeInventoryZ = StoreInventory::where([
                                    ['store_id', '=', $product['storeId']],
                                    ['productId', '=', $product['productId']]
                                ])->get();
                    // Log::info($storeInventoryZ);

                    if($storeInventoryZ->isEmpty() !=1)
                    {
                    foreach($storeInventoryZ as $storeInZ){
                        $storeId = $storeInZ->id;
                    }

                    $store = StoreInventory::find($storeId);

                    $onHand = $store->onHand;
                    $productIncoming = $store->productIncoming;
                    $mrp = $store->mrp;
                    $price = $store->price;
                    $variantId = $store->variant_id;
                    // $measuringType = $store->measuringType;
                    }
                    else{
                    $mrp = 0;
                    }

                    $newStore = new StoreInventory;

                    $newStore->onHand = $product['quantity'];
                    $newStore->productIncoming = $product['quantity'];
                    // $newStore->safety_stock = $product['quantity'];
                    $newStore->mrp = $product['mrp'];
                    // $newStore->measuringType = $measuringType;
                    $newStore->price = $product['unitPrice'];
                    $newStore->productId = $product['productId'];
                    $newStore->store_id = $product['storeId'];
                    $newStore->variant_id = $product['variantId'];
                    $newStore->variant_name = $product['variant'];

                    $newStore->save();

                    $history = new ProductInHistory;
                    $history->store = $product['storeId'];
                    $history->store_name = $product['store'];
                    $history->product = $product['productId'];
                    $history->product_name = $product['product'];
                    $history->quantity = $product['quantity'];
                    $history->unit_price = $product['unitPrice'];
                    $history->mrp = $product['mrp'];
                    $history->subscriber_id = Auth::user()->subscriber_id;
                    $history->user_id = Auth::user()->id;
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
                        $price->store_id = $product['storeId'];
                        $price->subscriber_id = Auth::user()->subscriber_id;
                        $price->product_in_num = $product['productInNum'];
                        $price->variant_id = $product['variantId'];
                        $price->variant_name = $product['variant'];
                        $price->save();
                    }

                    // return response()->json([
                    //     'status' => 200,
                    //     'message' => 'Product-In successfully!',
                    // ]);

                }else{
                    foreach($storeInventory as $storeIn){
                        $storeId = $storeIn->id;
                    }

                    $store = StoreInventory::find($storeId);

                        $onHand = $store->onHand;
                        $productIncoming = $store->productIncoming;
                        $mrp = $store->mrp;
                        $price = $store->price;
                        $variantId = $store->variant_id;
                    // $measuringType = $store->measuringType;


                    // Log::info($batch);
                    // Log::info((int)$product['batchNumber']);
                    // Log::info($product['batchNumber']);

                    // Log::info((int)$product['variantId']);
                    if($variantId == (int)$product['variantId']){
                        $store->onHand = $product['quantity'] + $onHand;

                        $store->productIncoming = $product['quantity'] + $productIncoming;

                        $store->mrp = $product['mrp'];
                        $store->price = $product['unitPrice'];

                        $store->save();
                    }else{
                        $newStore = new StoreInventory;

                        $newStore->onHand = $product['quantity'];
                        $newStore->productIncoming = $product['quantity'];
                        // $newStore->safety_stock = $product['quantity'];
                        $newStore->mrp = $product['mrp'];
                        // $newStore->measuringType = $measuringType;
                        $newStore->price = $product['unitPrice'];
                        $newStore->productId = $product['productId'];
                        $newStore->store_id = $product['storeId'];

                        if($product['variant'] == NULL){
                            $newStore->variant_id = 0;
                            $newStore->variant_name = 'default';
                        }else{
                            $newStore->variant_id = $product['variantId'];
                            $newStore->variant_name = $product['variant'];
                        }


                        $newStore->save();

                    }


                    $history = new ProductInHistory;
                    $history->store = $product['storeId'];
                    $history->store_name = $product['store'];
                    $history->product = $product['productId'];
                    $history->product_name = $product['product'];
                    $history->quantity = $product['quantity'];
                    $history->unit_price = $product['unitPrice'];
                    $history->mrp = $product['mrp'];
                    $history->subscriber_id = Auth::user()->subscriber_id;
                    $history->user_id = Auth::user()->id;
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
                        $price->store_id = $product['storeId'];
                        $price->subscriber_id = Auth::user()->subscriber_id;
                        $price->product_in_num = $product['productInNum'];
                        $price->variant_id = $product['variantId'];
                        $price->variant_name = $product['variant'];
                        $price->save();
                    }
                }

            }else{
                $inventorises = Inventory::where([
                                ['productId', '=', $product['productId'] ],
                                ['variant_id', '=', (int)$product['variantId'],
                                ['subscriber_id', Auth::user()->subscriber_id],]
                            ])->get();

                if($inventorises->isEmpty()){
                    $inventory = new Inventory;

                    $inventory->onHand = $product['quantity'];
                    $inventory->productIncoming = $product['quantity'];
                    $inventory->mrp = $product['mrp'];
                    // $inventory->measuringType = $measuringType;
                    $inventory->price = $product['unitPrice'];
                    // $inventory->purchase_date = $product['date'];
                    $inventory->productId = $product['productId'];
                    $inventory->variant_id = $product['variantId'];
                    $inventory->variant_name = $product['variant'];
                    $inventory->subscriber_id = Auth::user()->subscriber_id;

                    $inventory->save();

                    $price = new Price;
                    $price->product_id = $product['productId'];
                    $price->price = $product['unitPrice'];
                    $price->mrp = $product['mrp'];
                    $price->quantity = $product['quantity'];
                    $price->store_id = 0;
                    $price->subscriber_id = Auth::user()->subscriber_id;
                    $price->product_in_num = $product['productInNum'];
                    $price->variant_id = $product['variantId'];
                    $price->variant_name = $product['variant'];
                    $price->save();
                    // return response()->json([
                    //     'status' => 200,
                    //     'message' => 'You have to add the product in the Warehouse first.',
                    // ]);
                }
                else{
                foreach($inventorises as $inventory){
                    $inventoryId = $inventory->id;
                }

                // Log::info($inventoryId);
                if($inventoryId)
                {
                    $inventory = Inventory::find($inventoryId);

                    $onHand          = $inventory->onHand;
                    $productIncoming = $inventory->productIncoming;
                    $mrp             = $inventory->mrp;
                    $price           = $inventory->price;
                    $variantId       = $inventory->variant_id;
                    // $measuringType   = $inventory->measuringType;
                }
                // Log::info((int)$product['variantId']);
                if($variantId == (int)$product['variantId']){
                    $inventory->onHand = $product['quantity'] + $onHand;
                    $inventory->productIncoming = $product['quantity'] + $productIncoming;
                    $inventory->mrp = $product['mrp'];
                    $inventory->price = $product['unitPrice'];

                    $inventory->save();
                }else{
                    $inventory = new Inventory;

                    $inventory->onHand = $product['quantity'];
                    $inventory->productIncoming = $product['quantity'];
                    $inventory->mrp = $product['mrp'];
                    // $inventory->measuringType = $measuringType;
                    $inventory->price = $product['unitPrice'];
                    // $inventory->purchase_date = $product['date'];
                    $inventory->productId = $product['productId'];
                    $inventory->variant_id = $product['variantId'];
                    $inventory->variant_name = $product['variant'];
                    $inventory->subscriber_id = Auth::user()->subscriber_id;

                    $inventory->save();

                }
                }
                $history = new ProductInHistory;
                $history->store = 0;
                $history->store_name = "Inventory";
                $history->product = $product['productId'];
                $history->product_name = $product['product'];
                $history->quantity = $product['quantity'];
                $history->unit_price = $product['unitPrice'];
                $history->mrp = $product['mrp'];
                $history->subscriber_id = Auth::user()->subscriber_id;
                $history->user_id = Auth::user()->id;
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
                    $price->store_id = 0;
                    $price->subscriber_id = Auth::user()->subscriber_id;
                    $price->product_in_num = $product['productInNum'];
                    $price->variant_id = $product['variantId'];
                    $price->variant_name = $product['variant'];

                    $price->save();

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
                        ['storeId', $product['storeId']],
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
                    $productSerial->storeId = $product['storeId'];
                    $productSerial->serialNumber = $barcode;
                    $productSerial->purchaseId =  $product['productInNum'];

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
            'message' => 'Product-In successfully!',
        ]);

    }

    public function productInfo($id){
        $product = Product::find($id);
        return response()->json([
            'status'=>200,
            'productType'=>$product->type,
            'barcode'=>$product->barcode
        ]);
    }

    public function productSerial(Request $request){
        // log::info($request);
        foreach($request->serialNumbers AS $serialNumber)
        {
            $serial = ProductSerial::where([
                ['productId', $request->ProductId],
                ['variantId', $request->VariantId],
                ['serialNumber', $serialNumber['serialNumber']]
            ])->get();
            foreach($serial as $serialNumber){
                $number = $serialNumber->serialNumber;
            }
            if($serial->isNotEmpty()){
                return response() -> json([
                    'status'=>400,
                    'serialNumber'=>$number,
                    'error' => 'Serial Number Exists'
                ]);
            }
            $productSerial = new ProductSerial;
            $productSerial->productId = doubleval($request->ProductId);
            $productSerial->productName = $request->Product;
            $productSerial->variantId = doubleval($request->VariantId);
            $productSerial->variantName = $request->Variant;
            $productSerial->storeId = doubleval($request->StoreId);
            $productSerial->serialNumber = $serialNumber['serialNumber'];
            $productSerial->purchaseId =   'Product In';

            $purchaseDateStr                = strtotime($request->transferDate);
            $productSerial->purchaseDate  = date('Y-m-d', $purchaseDateStr);

            $productSerial->created_by = Auth::user()->name;
            $productSerial->subscriber_id =  Auth::user()->subscriber_id;
            // log::info($productSerial);

            $productSerial->save();
        }
        return response() -> json([
            'status'=>200,
            'message' => 'Serial Number Added Successfully'
        ]);
    }
}
