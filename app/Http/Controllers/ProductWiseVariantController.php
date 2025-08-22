<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Inventory;

use Illuminate\Http\Request;
use App\Models\StoreInventory;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductWiseVariantController extends Controller
{
    public function data(Request $request, $productId){
        $data = Product::join('variants', 'products.id', 'variants.product_id')
                ->select('variants.variant_name', 'variants.id')
                ->where([
                    ['variants.subscriber_id', Auth::user()->subscriber_id],
                    ['variants.product_id', $productId]
                ])
                ->get();


        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }

    public function storeWiseData(Request $request, $productId, $storeId)
    {
        $product=Product::find($productId);
        if($storeId=='inventory'){
            $data = Inventory::join('variants', 'inventories.productId', 'variants.product_id')
            ->select('inventories.variant_name', 'inventories.variant_id')
            ->where([
                ['variants.subscriber_id', Auth::user()->subscriber_id],
                ['inventories.productId', $productId],
                ['inventories.onHand', '>', 0]
            ])
                ->distinct()
                ->get();


            // Log::info($data);
            return response()->json([
                'data' => $data,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'message' => 'Success'
            ]);
        }
        else
        {
            $data = StoreInventory::join('variants', 'store_inventories.productId', 'variants.product_id')
            ->select('store_inventories.variant_name', 'store_inventories.variant_id')
            ->where([
                ['variants.subscriber_id', Auth::user()->subscriber_id],
                ['store_inventories.productId', $productId],
                ['store_inventories.store_id', $storeId],
                ['store_inventories.onHand', '>', 0]
            ])
                ->distinct()
                ->get();

            // Log::info($data);
            return response()->json([
                'data' => $data,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'message' => 'Success'
            ]);
        }
    }
}
