<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;

use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class ExpiredStockController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('stock/expired-stock', ['stores' => $stores]);
    }

    public function expiredStockData(Request $request){
        
        // $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
        //         ->where([['products.subscriber_id', Auth::user()->subscriber_id]])
        //         ->get();

        $data = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->join('stores', 'store_inventories.store_id', 'stores.id')
            ->join('variants', 'store_inventories.variant_id', 'variants.id')
            ->select("products.productName", "variants.variant_name", "variants.id", "store_inventories.onHand", 
                        "stores.store_name")
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
                ['variants.subscriber_id', '=', Auth::user()->subscriber_id]
            ])
            ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }

    public function expiredStoreStockData(Request $request, $storeId){

        if($storeId == 'option_select'){
            $data = DB::table("products")
                ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                ->join('stores', 'store_inventories.store_id', 'stores.id')
                ->join('variants', 'store_inventories.variant_id', 'variants.id')
                ->select("products.productName", "variants.variant_name", "variants.id", "store_inventories.onHand", 
                            "stores.store_name")
                ->where([
                    ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
                    ['variants.subscriber_id', '=', Auth::user()->subscriber_id]
                ])
                ->get();

            return response()->json([
                'data' => $data,
                'message' => 'Success'
            ]);
        }else{
            $data = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->join('stores', 'store_inventories.store_id', 'stores.id')
            ->join('batches', 'store_inventories.batch_number', 'batches.batch_number')
            ->join('variants', 'store_inventories.variant_id', 'variants.id')
            ->select("products.productName", "variants.variant_name", "variants.id", "store_inventories.onHand", 
                        "stores.store_name")
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
                ['variants.subscriber_id', '=', Auth::user()->subscriber_id],
                ['store_inventories.store_id', $storeId]
            ])
            ->get();

            return response()->json([
                'data' => $data,
                'message' => 'Success'
            ]);
        }
        
    }
}
