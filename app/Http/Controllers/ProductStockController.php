<?php

namespace App\Http\Controllers;

use App\Models\Vat;
use App\Models\Brand;

use App\Models\Price;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Subcategory;
use App\Models\StoreProduct;


use App\Models\VariantImage;
use Illuminate\Http\Request;
use App\Models\StoreInventory;




use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Validator;

class ProductStockController extends Controller
{
    public function create($productId, $variantId){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['product_id', $productId],
            ['id', $variantId]
        ])->get();
        $product = Product::where([
            ['subscriber_id', Auth::user()->subscriber_id],
            ['id', $productId]
        ])->first();
        return view('product/product-stock', ['stores' => $stores, 'variants' => $variants, 'productId' => $productId, 'productName' => $product->productName]);
    }

    public function store(Request $request){
        log::info($request);

        foreach($request->stores as $store){
            if($store['storeId'] == 'warehouse'){

                $inventorises = Inventory::where([
                    ['productId', '=', $request->productId ],
                    ['variant_id', '=', $store['variantId'] ]
                ])->get();


                if($inventorises->isEmpty()){
                   $inventory = new Inventory;
                    $inventory->onHand = $store['qty'];
                    $inventory->productIncoming = $store['qty'];
                    $inventory->productOutgoing = 0;
                    $inventory->mrp = $store['price'];
                    $inventory->price = $store['purchaseCost'];
                    $inventory->productId = $request->productId;
                    $inventory->variant_id = $store['variantId'];
                    $inventory->variant_name = $store['variantName'];
                    $inventory->safety_stock = $store['safetyStock'];

                    $price = new Price;
                    $price->product_id = $request->productId;
                    $price->price = doubleval($store['purchaseCost']);
                    $price->mrp = doubleval($store['price']);
                    $price->quantity = doubleval($store['qty']);
                    $price->subscriber_id = Auth::user()->subscriber_id;
                    $price->store_id = 0;
                    $price->product_in_num = 0;
                    $price->variant_id = (int)$store['variantId'];
                    $price->variant_name = $store['variantName'];

                    $price->save();
                    $inventory->save();
                }else{
                    foreach($inventorises as $inventory){
                        $inventoryId = $inventory->id;
                        $inventory = Inventory::find($inventoryId);
                        $onHand          = $inventory->onHand;

                        $productIncoming = $inventory->productIncoming;
                        $mrp             = $inventory->mrp;
                        $price           = $inventory->price;
                        $variantId       = $inventory->variant_id;

                        if($variantId == (int)$store['variantId']){
                            $inventory->onHand = $store['qty'] + $onHand;
                            $inventory->productIncoming = $store['qty'] + $productIncoming;
                            $inventory->mrp = $store['price'];
                            $inventory->price = $store['purchaseCost'];
                            $inventory->safety_stock = $store['safetyStock'];

                            $inventory->save();
                        }else{
                            $inventory = new Inventory;

                            $inventory->onHand = $store['qty'];
                            $inventory->productIncoming = $store['qty'];
                            $inventory->mrp = $store['price'];
                            $inventory->price = $store['purchaseCost'];
                            $inventory->productId = $request->productId;
                            $inventory->variant_id = $store['variantId'];
                            $inventory->variant_name = $store['variantName'];
                            $inventory->safety_stock = $store['safetyStock'];
                            $inventory->save();

                        }

                        if( $mrp != $store['price'] || $price != $store['purchaseCost'] ){

                            $price = new Price;

                            $price->product_id = $request->productId;
                            $price->price = doubleval($store['purchaseCost']);
                            $price->mrp = doubleval($store['price']);
                            $price->quantity = (int)$store['qty'];
                            $price->store_id = 0;
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->product_in_num = 0;
                            $price->variant_id = $store['variantId'];
                            $price->variant_name = $store['variantName'];

                            $price->save();

                        }

                    }
                }


            }else{
                $storeInventory = StoreInventory::where([
                    ['store_id', '=', $store['storeId']],
                    ['productId', '=', $request->productId],
                    ['variant_id', '=', (int)$store['variantId']]
                ])->get();

                 if($storeInventory->isEmpty()){

                    // Log::info('Empty');
                    $shop = new StoreInventory;
                    $shop->onHand = $store['qty'];
                    $shop->productIncoming = $store['qty'];
                    $shop->productOutgoing = 0;
                    $shop->safety_stock = $store['safetyStock'];
                    $shop->mrp = $store['price'];
                    $shop->price = $store['purchaseCost'];
                    $shop->productId = $request->productId;
                    $shop->store_id = $store['storeId'];
                    $shop->variant_id = $store['variantId'];
                    $shop->variant_name = $store['variantName'];


                    $price = new Price;
                    $price->product_id = $request->productId;
                    $price->price = doubleval($store['purchaseCost']);
                    $price->mrp = doubleval($store['price']);
                    $price->quantity = doubleval($store['qty']);
                    $price->subscriber_id = Auth::user()->subscriber_id;
                    $price->store_id = $store['storeId'];
                    $price->product_in_num = 0;
                    $price->variant_id = (int)$store['variantId'];
                    $price->variant_name = $store['variantName'];

                    $price->save();
                    $shop->save();
                 }else{

                    foreach($storeInventory as $storeIn){
                        $storeId = $storeIn->id;
                    }
                    // Log::info('Not Empty');



                    $storeX = StoreInventory::find($storeId);
                    $onHand = $storeX->onHand;
                    $productIncoming = $storeX->productIncoming;
                    $mrp = $storeX->mrp;
                    $price = $storeX->price;
                    $variantId = $storeX->variant_id;


                    //  Log::info($onHand);

                    $storeX->onHand = $store['qty'] + $onHand;
                    $storeX->productIncoming = $store['qty'] + $productIncoming;
                    $storeX->mrp = $store['price'];
                    $storeX->price = $store['purchaseCost'];
                    $storeX->safety_stock = $store['safetyStock'];
                    $storeX->save();

                    if( $mrp != $store['price'] || $price != $store['purchaseCost'] ){

                        $price = new Price;
                        $price->product_id = $request->productId;
                        $price->price = doubleval($store['purchaseCost']);
                        $price->mrp = doubleval($store['price']);
                        $price->quantity = doubleval($store['qty']);
                        $price->subscriber_id = Auth::user()->subscriber_id;
                        $price->store_id = $store['storeId'];
                        $price->product_in_num = 0;
                        $price->variant_id = (int)$store['variantId'];
                        $price->variant_name = $store['variantName'];
                        $price->save();
                    }
                }


            }
        }

        return response() -> json([
            'status'=> 200,
            'message' => 'Product added Successfully!'
        ]);



    }
}
