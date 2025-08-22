<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class StoreStockController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('stock/store-stock', ['stores' => $stores]);
    }

    public function storeStockDataDefault(Request $request){
        $defaultStore = Store::where('subscriber_id', Auth::user()->subscriber_id)->first();

        // $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
        //         ->where([['products.subscriber_id', Auth::user()->subscriber_id], ['store_inventories.store_id', $defaultStore->id]])
        //         ->get();

        $data = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->select(DB::raw("SUM(store_inventories.onHand) as totalOnHand"), DB::raw("SUM(store_inventories.productIncoming) as totalProductIncoming"),
                    DB::raw("SUM(store_inventories.productOutgoing) as totalProductOutgoing"), "store_inventories.productId", "store_inventories.mrp","store_inventories.price", 
                    "products.productName", "products.brand",)
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
                ['store_inventories.store_id', '=', $defaultStore->id]
            ])
            ->groupBy("store_inventories.productId", "store_inventories.mrp","store_inventories.price", 
                    "products.productName", "products.brand")
            ->get();

        return response()->json([
            'storename' => $defaultStore->store_name,
            'store_address' => $defaultStore->store_address,
            'data' => $data,
            'message' => 'Success'
        ]);

    }

    public function storeStockData(Request $request, $storeId){

        // $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
        //         ->where([['products.subscriber_id', Auth::user()->subscriber_id], ['store_inventories.store_id', $storeId]])
        //         ->get();

        $data = DB::table("products")
        ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
        ->select(DB::raw("SUM(store_inventories.onHand) as totalOnHand"), DB::raw("SUM(store_inventories.productIncoming) as totalProductIncoming"),
                DB::raw("SUM(store_inventories.productOutgoing) as totalProductOutgoing"), "store_inventories.productId", "store_inventories.mrp","store_inventories.price", 
                "products.productName", "products.brand",)
        ->where([
            ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
            ['store_inventories.store_id', $storeId]
        ])
        ->groupBy("store_inventories.productId", "store_inventories.mrp","store_inventories.price", 
                "products.productName", "products.brand")
        ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }
}
