<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;

use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class LowStockController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('stock/low-stock', ['stores' => $stores]);
    }

    public function lowStockData(Request $request){

        $data = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->join('stores', 'store_inventories.store_id', 'stores.id')
            ->select("products.productName", "products.brand", "products.productLabel", "store_inventories.safety_stock", "store_inventories.onHand", "stores.store_name", "store_inventories.variant_id", "store_inventories.variant_name")
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ]
            ])
            ->get();

        $dataArray = [];
        foreach($data as $item){
            if($item->onHand <= $item->safety_stock){
                $x = [
                    "productName" => $item->productName,
                    "brand" => $item->brand,
                    "productLabel" => $item->productLabel,
                    "safety_stock" => $item->safety_stock,
                    "onHand" => $item->onHand,
                    "store_name" => $item->store_name,
                    "variant_id" => $item->variant_id,
                    "variant_name" => $item->variant_name,
                ];

                $dataArray[] =  $x;
            }
        }

        return response()->json([
            'data' => $dataArray,
            'message' => 'Success'
        ]);
    }

    public function lowStocStorekData(Request $request, $storeId){

        if($storeId == 'option_select'){
            $data = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->join('stores', 'store_inventories.store_id', 'stores.id')
            ->select("products.productName", "products.brand", "products.productLabel", "store_inventories.safety_stock", "store_inventories.onHand", "stores.store_name", "store_inventories.variant_id", "store_inventories.variant_name")
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ]
            ])
            ->get();

            $dataArray = [];
            foreach($data as $item){
                if($item->onHand <= $item->safety_stock){
                    $x = [
                        "productName" => $item->productName,
                        "brand" => $item->brand,
                        "productLabel" => $item->productLabel,
                        "safety_stock" => $item->safety_stock,
                        "onHand" => $item->onHand,
                        "store_name" => $item->store_name,
                        "variant_id" => $item->variant_id,
                        "variant_name" => $item->variant_name,
                    ];

                    $dataArray[] =  $x;
                }
            }

            return response()->json([
                'data' => $dataArray,
                'message' => 'Success'
            ]);
        }else{
            $data = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->join('stores', 'store_inventories.store_id', 'stores.id')
            ->select("products.productName", "products.brand", "products.productLabel", "store_inventories.safety_stock", "store_inventories.onHand", "stores.store_name", "store_inventories.variant_id", "store_inventories.variant_name")
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
                ['store_inventories.store_id', $storeId]
            ])
            ->get();

            $dataArray = [];
            foreach($data as $item){
                if($item->onHand <= $item->safety_stock){
                    $x = [
                        "productName" => $item->productName,
                        "brand" => $item->brand,
                        "productLabel" => $item->productLabel,
                        "safety_stock" => $item->safety_stock,
                        "onHand" => $item->onHand,
                        "store_name" => $item->store_name,
                        "variant_id" => $item->variant_id,
                        "variant_name" => $item->variant_name,
                    ];

                    $dataArray[] =  $x;
                }
            }

            return response()->json([
                'data' => $dataArray,
                'message' => 'Success'
            ]);
        }
        
    }
}
