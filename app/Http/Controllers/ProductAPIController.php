<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\TransactionID;
use App\Models\StoreInventory;
use App\Models\PaymentDirection;
use App\Http\Controllers\Controller;
use App\Http\Services\ProductService;

class ProductAPIController extends Controller
{
    public function transaction(Request $request)
    {
        $transaction = new TransactionID;
        $transaction->transactionid = $request->transactionid;
        $transaction->type = $request->type;
        $transaction->save();

        return response()->json([
            "status" => "success",


        ]);
    }
    public function list(Request $request, $subscriberId, $storeId)
    {


        $data1 = DB::table("products")
            ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
            ->select("store_inventories.*", "products.*")
            ->where([
                ['products.subscriber_id', $subscriberId],
                ['store_inventories.store_id', $storeId]
            ])
            ->groupBy("store_inventories.variant_id")
            ->where(function ($query) {
                $query->select("mrp", "price")
                    ->from('prices')
                    // ->whereColumn('prices.product_id',  'products.id')
                    ->orderBy('prices.id')
                    ->limit(1);
            })
            ->get();


        // $data1 = DB::table("products")
        //     ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
        //     ->select("products.*", "store_inventories.*")

        //     ->where([
        //             ['products.subscriber_id', $subscriberId],
        //             ['store_inventories.store_id', $storeId]
        //         ])
        //     // ->groupBy("store_inventories.variant_id")
        //     // ->take(1)
        //     ->get();

        // return $data1;

        $data_array = [];
        foreach ($data1 as $d1) {

            // $data = Product::join('store_inventories', 'products.id', 'store_inventories.productId')
            // ->where('products.id', $d1->productId)->first();
            // // Log::info($data);
            // $mrps = DB::table("products")
            //         ->join('prices', 'products.id', '=', 'prices.product_id')
            //         ->select("prices.mrp", "prices.price")
            //         ->where([
            //             ['prices.store_id', $storeId],
            //             ['products.id', $data->productId],
            //             ['prices.product_id',  $data->productId]
            //         ])
            //         ->orderBy('prices.id', 'desc')
            //         ->take(1)
            //         // ->get();
            //         ->first();

            if ($d1->mrp == NULL) {
                $mrp = 0;
                $price = 0;
            } else {
                $mrp = $d1->mrp;
                $price = $d1->price;
            }

            $x = [
                'id' => $d1->id,
                'productName' => $d1->productName,
                'productLabel' => $d1->productLabel,
                'brand' => $d1->brand,
                'category' => $d1->category,
                'category_name' => $d1->category_name,
                'subcategory' => $d1->subcategory,
                'subcategory_name' => $d1->subcategory_name,
                'variant_id' => $d1->variant_id,
                'variant_name' => $d1->variant_name,
                'sku' => $d1->sku,
                'barcode' => $d1->barcode,
                'supplier' => $d1->supplier,
                'safety_stock' => $d1->safety_stock,
                'type' => $d1->type,
                'available_discount' => $d1->available_discount,
                'discount' => $d1->discount,
                'discount_type' => $d1->discount_type,
                'available_offer' => $d1->available_offer,
                'offerItemId' => $d1->offerItemId,
                'freeItemName' => $d1->freeItemName,
                'requiredQuantity' => $d1->requiredQuantity,
                'freeQuantity' => $d1->freeQuantity,
                'isExcludedTax' => $d1->isExcludedTax,
                'taxName' => $d1->taxName,
                'tax' => $d1->tax,
                // 'desc' => $d1->desc,
                'productImage' => $d1->productImage,
                'subscriber_id' => $d1->subscriber_id,
                // 'shelf' => $d1->shelf,
                // 'batch_number' => $d1->batch_number,
                // 'expiry_date' => $d1->expiry_date,
                // 'box_size' => $d1->box_size,
                // 'strength' => $d1->strength,
                "created_by" => $d1->created_by,
                "updated_by" => $d1->updated_by,
                "created_at" => $d1->created_at,
                "updated_at" => $d1->updated_at,
                'onHand' => $d1->onHand,
                "productIncoming" => $d1->productIncoming,
                "productOutgoing" => $d1->productOutgoing,
                'mrp' => $mrp,
                // "measuringType"=> $d1->measuringType,
                'price' => $price,
                'productId' => $d1->productId,
                "store_id" => $d1->store_id,

            ];

            $data_array[] = $x;
        }

        return response()->json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $data_array,
            // "data" => $data,
        ]);
    }

    public function productVariantList(Request $request, $subscriberId, $storeId)
    {

        $storeInventorys = StoreInventory::Where('store_id', $storeId)->distinct('productId')->get('productId');
        $data_array = [];
        foreach ($storeInventorys as $storeInventory) {
            $product = Product::find($storeInventory->productId);

            $productVariants = DB::table("store_inventories")->join('variants', 'store_inventories.variant_id', 'variants.id')
                ->where([
                    ['store_inventories.productId', $storeInventory->productId],
                    ['store_inventories.store_id', $storeId],
                ])
                ->get();

            $variant_array = [];
            $totalStoreQuantity = 0;
            foreach ($productVariants as $productVariant) {
                $productSerials = ProductSerial::Select('serialNumber')->Where([
                    ['subscriber_id', $subscriberId],
                    ['storeId', $storeId],
                    ['productId', $productVariant->productId],
                    ['variantId', $productVariant->variant_id],
                    ['saleId', 0]
                ])->get();

                $totalStoreQuantity = $totalStoreQuantity + $productVariant->onHand;
                $v = [
                    'onHand' => $productVariant->onHand,
                    'safety_stock' => $productVariant->safety_stock,
                    'mrp' => $productVariant->mrp,
                    'price' => $productVariant->price,
                    'subscriber_id' => $productVariant->subscriber_id,

                    'variant_id' => $productVariant->variant_id,
                    'variant_name' => $productVariant->variant_name,
                    'variant_measurement' => $productVariant->variant_measurement,
                    'variant_description' => $productVariant->variant_description,
                    'available_discount' => $productVariant->available_discount,
                    'discount_type' => $productVariant->discount_type,
                    'discount' => $productVariant->discount,
                    'taxName' => $productVariant->taxName,
                    'isExcludedTax' => $productVariant->isExcludedTax,
                    'tax' => $productVariant->tax,

                    'serialNumbers' => $productSerials,
                ];
                $variant_array[] = $v;
            }
            // log::alert($product);
            $x = [
                'id' => $product->id,
                'productName' => $product->productName,
                'productLabel' => $product->productLabel,
                'brand' => $product->brand,
                'category' => $product->category,
                'category_name' => $product->category_name,
                'subcategory' => $product->subcategory,
                'subcategory_name' => $product->subcategory_name,
                'type' => $product->type,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'supplier' => $product->supplier,
                'subscriber_id' => $product->subscriber_id,
                'totalStoreQuantity' => $totalStoreQuantity,
                "variants" => $variant_array,
            ];
            $data_array[] = $x;
        }
        return response()->json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $data_array,
            // "data" => $data,
        ]);
    }

    public function productlist($subscriberId)
    {
        $data1 = DB::table("products")
            ->where([
                ['products.type', 'Non-Serialize'],
                ['products.subscriber_id', $subscriberId],
            ])
            ->get();
        foreach ($data1 as $data) {
            $data2 = DB::table("variants")
                ->where([
                    ['variants.subscriber_id', $subscriberId],
                    ['variants.product_id', $data->id]
                ])
                ->get();
            $data->variants = $data2;
        }
        return response()->json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "products" => $data1,
        ]);
    }

    public function serializeProductlist($subscriberId)
    {
        $data1 = DB::table("products")
            ->where([
                ['products.type', 'Serialize'],
                ['products.subscriber_id', $subscriberId],
            ])
            ->get();
        foreach ($data1 as $data) {
            $data2 = DB::table("variants")
                ->where([
                    ['variants.subscriber_id', $subscriberId],
                    ['variants.product_id', $data->id]
                ])
                ->get();
            $data->variants = $data2;
        }
        return response()->json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "products" => $data1,
        ]);
    }

    public function lowStock($subscriberId, $storeId)
    {
        return (new ProductService)->productLowStock($subscriberId, $storeId);
    }

    public function unitStore(Request $request, $subscriberId)
    {
        return (new ProductService)->unitStore($request, $subscriberId);
    }

    public function unitList($subscriberId)
    {
        return (new ProductService)->unitList($subscriberId);
    }
    public function payment_direction()
    {
        $payment_direction = PaymentDirection::first();

        return response()->json(
            $payment_direction


        );
    }
}
