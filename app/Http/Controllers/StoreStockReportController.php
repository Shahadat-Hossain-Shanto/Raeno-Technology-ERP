<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Auth;

class StoreStockReportController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('report/store-stock-report', ['stores' => $stores]);
    }

    public function storeStockData(Request $request, $storeId){

        $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
                ->where([['products.subscriber_id', Auth::user()->subscriber_id], ['store_inventories.store_id', $storeId]])
                ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }

    public function storeStockDataDefault(Request $request){
        $defaultStore = Store::where('subscriber_id', Auth::user()->subscriber_id)->first();

        $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
                ->where([['products.subscriber_id', Auth::user()->subscriber_id], ['store_inventories.store_id', $defaultStore->id]])
                ->get();

        return response()->json([
            'storename' => $defaultStore->store_name,
            'store_address' => $defaultStore->store_address,
            'data' => $data,
            'message' => 'Success'
        ]);

    }

    public function getStoreSerial($id){

        $inventory = StoreInventory::find($id);

        $serial = ProductSerial::join('products', 'products.id', 'product_serials.productId')->where([
            ['product_serials.productId', $inventory->productId],
            ['product_serials.variantId', $inventory->variant_id],
            ['product_serials.storeId', $inventory->store_id],
            ['product_serials.saleId', 0],
            ['product_serials.subscriber_id', Auth::user()->subscriber_id],
        ])
        ->select('product_serials.productName','products.type', 'product_serials.variantName', 'product_serials.serialNumber')->get();

        return response()->json([
            'data' => $serial,
            'message' => 'Success'
        ]);
    }
}
