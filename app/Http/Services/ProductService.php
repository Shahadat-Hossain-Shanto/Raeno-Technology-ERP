<?php

namespace App\Http\Services;

use Session;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductUnit;
use App\Models\Subcategory;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Validator;

class ProductService
{
    public function create($subscriberId){
        Session::put('subscriberId', $subscriberId);
        $categories = Category::select('id','category_name')->where('subscriber_id', $subscriberId)->get();
        $subcategory = Subcategory::select('id','subcategory_name')->get();
        $brands = Brand::select('id','brand_name')->where('subscriber_id', $subscriberId)->get();
        // $discounts = Discount::where('subscriber_id', $subscriberId)->get();
        // $taxs = Vat::where('subscriber_id', $subscriberId)->get();
        $suppliers = Supplier::select('id','name')->where('subscriber_id', $subscriberId)->get();
        $units= ProductUnit::select('id','name')->where('subscriber_id', $subscriberId)->get();

        // $categories = Category::select('id','category_name')->get();
        // $subcategory = Subcategory::select('id','subcategory_name')->get();
        // $brands = Brand::select('id','brand_name')->get();
        // // $discounts = Discount::get();
        // // $taxs = Vat::get();
        // $suppliers = Supplier::select('id','name')->get();
        // $units= ProductUnit::select('id','name')->get();

        return response() -> json([
            "message" => "success",
            "status" => 200,
            "categories" => $categories,
            "subcategory" => $subcategory,
            "brands" => $brands,
            "suppliers" => $suppliers,
            "units" => $units,
            // "discounts" => $discounts,
            // "taxs" => $taxs
        ]);
    }

    public function store($req, $subscriberId){

        Session::put('subscriberId', $subscriberId);
        // Log::info($req);
        // $req =  json_decode($request['json']);
        // Log::info('Solved '.$req->productName);
        // Log::info('Json '.$request['json']);
        // Log::info('Image '.$request['image']);

        // return $request['image'];

    //PRODUCT-----------------------------------------------------------------------------------------------------------------

    $messages = [
        'productName.required'      =>   "Product name is required.",
        'productLabel.required'     =>   "Product label is required.",
        'brand.required'            =>   "Brand name is required.",
        'category.required'         =>   "Category is required.",
    ];

    $validator = Validator::make($req->all(), [
        'productName'       => 'required',
        'productLabel'      => 'required',
        'brand'             => 'required',
        'category'          => 'required',
    ], $messages);

    if ($validator->passes()) {
        $product = Product::where([
            ['productName',$req->productName],
            ['productLabel',$req->productLabel],
            ['brand',$req->brand],
            ['category_name',$req->categoryName],
            ['subscriber_id',$subscriberId]
        ])->get();
        if($product->isEmpty())
        {

            $product = new Product;
            $product->productName               = $req->productName;
            $product->productLabel              = $req->productLabel;
            $product->brand                     = $req->brand;

            $product->category                  = $req->category;
            $product->category_name             = $req->categoryName;

            $product->subcategory               = $req->subcategory;
            $product->subcategory_name          = $req->subcategoryName;

            $product->type                      = $req->type;
            $product->sku                       = $req->sku;
            $product->barcode                   = $req->barcode;
            $product->supplier	                = $req->supplier;
            $product->subscriber_id             = $subscriberId;
            $product->save();

            foreach($req->variants as $variant){
                $newVariant = new Variant;
                $newVariant->variant_name = $variant['variantName'];
                $newVariant->variant_measurement = $variant['variantMeasurement'];
                $newVariant->variant_description = $variant['variantDescription'];
                $newVariant->available_discount = $variant['available_discount'];
                $newVariant->discount_type = $variant['discount_type'];
                $newVariant->discount = $variant['discount'];
                $newVariant->taxName = $variant['taxName'];
                $newVariant->isExcludedTax = $variant['isExcludedTax'];
                $newVariant->tax = $variant['tax'];
                $newVariant->variant_image = $variant['variantimage'];
                $newVariant->product_id = $product->id;
                $newVariant->subscriber_id = $subscriberId;
                $newVariant->save();
            }

            return response() -> json([
                'status'=>200,
                'message' => 'Product added Successfully!'
            ]);
        }
        else{
            return response() -> json([
                'status'=>400,
                'message' => 'Product Exist!'
            ]);
        }
    }

    return response()->json(['error'=>$validator->errors()]);

    }

    public function products(){
        $products = Product::Select('id','productName','strength')->get();
        return response() -> json([
            "message" => "success",
            "status" => 200,
            "products" => $products
        ]);
    }

    public function productLowStock($subscriberId, $storeId){
        $products = StoreInventory::join('products', 'products.id', 'store_inventories.productId')
        ->Select('products.id','products.productName','products.category_name', 'store_inventories.onHand', 'store_inventories.safety_stock', 'store_inventories.variant_name')
        ->where('store_inventories.store_id', $storeId)
        ->get();

        $stocks=[];
        foreach($products AS $product){
            if($product->onHand < $product->safety_stock)
            {
                $stock['id']=$product->id;
                $stock['name']=$product->productName.' - '.$product->variant_name;
                $stock['categoryName']=$product->category_name;
                $stock['onHand']=$product->onHand;
                $stock['safetyStock']=$product->safety_stock;

                $stocks[] = $stock;
            }
        }
        return response() -> json($stocks);
    }

    public function unitStore($request,$subscriberId){

        Session::put('subscriberId', $subscriberId);
        $messages = [
            'name.required'  =>    "Unit name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $unit = new ProductUnit;

            $unit->name = $request->name;
            $unit->description = $request->description;
            $unit->subscriber_id = $subscriberId;
            // $unit->user_id = $userId;

            $unit->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Unit created successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    public function unitList($subscriberId){
        $unit = ProductUnit::where('subscriber_id', $subscriberId)->select('name', 'description')->get();
        return response()->json($unit);
    }
}
