<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class StoerWiseProductController extends Controller
{
    public function data(Request $request, $storeId){

        $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
                ->select('products.productName', 'products.id')
                ->where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['store_inventories.store_id', $storeId]
                ])
                ->groupBy('products.productName', 'products.id')
                ->orderBy('products.id', 'asc')
                ->get();


        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }

    public function inventoryData(Request $request){
        // $data = Product::join('inventories', 'products.id', 'inventories.productId')
        // ->where('products.subscriber_id', Auth::user()->subscriber_id)->get();
        // return response()->json([
        //     'data' => $data,
        //     'message' => 'Success'
        // ]);
        $data = Product::join('inventories', 'products.id', 'inventories.productId')
                ->select('products.productName', 'products.id')
                ->where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                ])
                ->groupBy('products.productName', 'products.id')
                ->orderBy('products.id', 'asc')
                ->get();


        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }

    public function getProductPrice($productId, $variantId)
    {
        $products = DB::table("products")
        ->join('prices', 'products.id', '=', 'prices.product_id')
        ->select("prices.mrp", "prices.price")
        ->where([
            ['products.subscriber_id', Auth::user()->subscriber_id],
            ['products.id', $productId],
            ['prices.product_id', $productId],
            ['prices.variant_id', $variantId],
        ])
            ->orderBy('prices.id', 'desc')
            ->take(1)
            ->get();

        return response()->json([
            'message' => 'Success',
            'products' => $products,
        ]);
    }
}
