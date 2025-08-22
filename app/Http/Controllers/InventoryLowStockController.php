<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;

use Illuminate\Support\Facades\Auth;

use DB;
use Log;

class InventoryLowStockController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('stock/inventory-low-stock', ['stores' => $stores]);
    }

    public function lowStockData(Request $request){

        $data = DB::table("products")
            ->join('inventories', 'products.id', '=', 'inventories.productId')
            ->select("products.productName", "products.brand", "products.productLabel", "inventories.safety_stock", "inventories.onHand", "inventories.variant_name", "inventories.variant_id")
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
