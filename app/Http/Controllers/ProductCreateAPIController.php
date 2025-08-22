<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Http\Services\ProductService;
use Illuminate\Support\Facades\Validator;

class ProductCreateAPIController extends Controller
{
    public function create($subscriberId){
        return (new ProductService)->create($subscriberId);
    }

    public function store(Request $request, $subscriberId){
        return (new ProductService)->store($request, $subscriberId);
    }

    public function store2(Request $req,$subscriber_id){

        //PRODUCT-----------------------------------------------------------------------------------------------------------------
        Session::put('subscriberId', $subscriber_id);
        $stores = Store::where('subscriber_id', $subscriber_id)->get();
        $variants = Variant::where('subscriber_id',$subscriber_id)->get();

        $messages = [
            'productName.required'    =>   "Product name is required.",
            'productLabel.required'   =>   "Product label is required.",
            'brand.required'      =>   "Brand name is required.",
            'category.required'     =>     "Category is required.",
            'type.required'     =>   "Type is required."


        ];

        $validator = Validator::make($req->all(), [
            'productName'    => 'required',
            'productLabel'   => 'required',
            'brand'          => 'required',
            'category'      =>  'required',
            'type'      => 'required'
        ], $messages);

        if ($validator->passes()) {
            // Log::info('Success');
                $product = new Product;

                $product->productName               = $req->productName;
                $product->productLabel              = $req->productLabel;
                $product->brand                     = $req->brand;

                $product->category                  = $req->category;
                $product->category_name             = $req->category_name;

                $product->subcategory               = $req->subcategory;
                $product->subcategory_name          = $req->subcategory_name;

                $product->type                      = $req->type;
                $product->sku                       = $req->sku;
                $product->barcode                   = $req->barcode;
                $product->supplier	                = $req->supplier;

                $product->discount_type             = $req->discount_type;
                $product->available_discount        = $req->available_discount;

                if(doubleval($req->discount) == NULL){
                    $product->discount      = 0;
                }else{
                    $product->discount                  = doubleval($req->discount);
                }

                $product->offerItemId               = $req->offerItemId;
                $product->available_offer           = $req->available_offer;
                $product->freeItemName              = $req->freeItemName;

                if(doubleval($req->requiredQuantity) == NULL){
                    $product->requiredQuantity      = 0;
                }else{
                    $product->requiredQuantity      = doubleval($req->requiredQuantity);
                }

                if(doubleval($req->freeQuantity) == NULL){
                    $product->freeQuantity          = 0;
                }else{
                    $product->freeQuantity          = doubleval($req->freeQuantity);
                }

                $product->taxName                   = $req->taxName;
                $product->isExcludedTax             = $req->isExcludedTax;

                if(doubleval($req->tax) == NULL){
                    $product->tax      = 0;
                }else{
                    $product->tax                       = doubleval($req->tax);
                }

                $product->subscriber_id             = $subscriber_id;

                $product->productImage              = $req->productImage;

                $product->save();

                foreach($req->variants as $variant){
                    // Log::info($variant);
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
                    $newVariant->subscriber_id = $subscriber_id;
                    $newVariant->save();
                }

            return view('product/product-stock', [ 'stores' => $stores, 'variants' => $variants, 'productId'=>$product->id ]);
            return redirect('product-stock-create')->with('productId', $product->id);
            return response() -> json([
                'status'=> 200,
                'productId' => $product->id,
                'message' => 'Product added Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }



}
