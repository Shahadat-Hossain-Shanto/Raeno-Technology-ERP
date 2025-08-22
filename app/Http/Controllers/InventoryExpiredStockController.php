<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;

use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class InventoryExpiredStockController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('stock/inventory-expired-stock', ['stores' => $stores]);
    }

    public function expiredStockData(Request $request){
        
        // $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
        //         ->where([['products.subscriber_id', Auth::user()->subscriber_id]])
        //         ->get();

        $data = DB::table("products")
            ->join('inventories', 'products.id', '=', 'inventories.productId')
            ->join('batches', 'inventories.batch_number', 'batches.batch_number')
            ->select("products.productName", "batches.batch_number", "batches.expiry_date", "inventories.onHand")
            ->where([
                ['products.subscriber_id', '=', Auth::user()->subscriber_id ],
                ['batches.subscriber_id', '=', Auth::user()->subscriber_id]
            ])
            ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }
}
