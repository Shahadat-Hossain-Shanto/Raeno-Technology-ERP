<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;

use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class WarehouseStockReportController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('stock/warehouse-stock', ['stores' => $stores]);
    }

    public function inventoryStockData(Request $request){

        // $data = Product::join('inventories', 'products.id', 'inventories.productId')
        //         ->where('products.subscriber_id', Auth::user()->subscriber_id)
        //         ->get();
        
        $data = DB::table("products")
            ->join('inventories', 'products.id', '=', 'inventories.productId')
            ->select(DB::raw("SUM(inventories.onHand) as totalOnHand"), DB::raw("SUM(inventories.productIncoming) as totalProductIncoming"),
                    DB::raw("SUM(inventories.productOutgoing) as totalProductOutgoing"), "inventories.productId", "inventories.mrp","inventories.price", 
                    "products.productName", "products.brand",)
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ]
            ])
            ->groupBy("inventories.productId", "inventories.mrp","inventories.price", 
                    "products.productName", "products.brand")
            ->get();

        // Log::info($data);

        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }
}
