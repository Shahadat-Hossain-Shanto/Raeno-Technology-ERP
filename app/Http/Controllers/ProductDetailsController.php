<?php

namespace App\Http\Controllers;

use App\Models\Vat;
use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductDetailsController extends Controller
{
    public function index($productId){
        $units = ProductUnit::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $taxs = Vat::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('product/product-details', ["productId" => $productId, "units"=>$units, "taxs"=>$taxs]);
    }

    public function show($productId){
        $data = Product::where([
            ['products.id', $productId]
            // ['subscriber_id', Auth::user()->subscriber_id]
        ])->first();

        $variants = Variant::where('product_id',$productId)->get();
        return response()->json([
                'data'=>$data,
                'variants'=>$variants
            ]);
    }

    public function addVariant(Request $request, $id){
        $messages = [
            'variant_name.required'             =>   "Product name is required.",
            'variant_measurement.required'      =>   "Brand name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'variant_name'          => 'required',
            'variant_measurement'   => 'required',
        ], $messages);

        if ($validator->passes()) {
            $variant = Variant::where([
                ['variant_name',$request->variant_name],
                ['product_id',$id],
                ])->get();
            if($variant->isEmpty()){
                $variant = new Variant;
                $variant->variant_name          =$request->variant_name;
                $variant->variant_measurement   =$request->variant_measurement;
                $variant->variant_description   =$request->variant_description;
                // $variant->available_discount    =$request->available_discount;
                // $variant->discount_type         =$request->discount_type;
                // $variant->discount              =$request->discount;
                // $variant->taxName               =$request->taxName;
                // $variant->isExcludedTax         =$request->isExcludedTax;
                // $variant->tax                   =$request->tax;
                $variant->product_id            =$id;
                $variant->subscriber_id         =Auth::user()->subscriber_id;
                $variant->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Variant Added Successfully'
                ]);
            }
            else{
                return response()->json([
                    'status'=>400,
                    'message'=>'Variant Exist'
                ]);
            }
        }
        else{
            return response()->json([
                'status'=>400,
                'message'=>'Fillup Required Fields'
            ]);
        }
    }

    public function getVariant($productId, $variantId){
        $variant = Variant::where([
            ['id',$variantId],
            ['product_id',$productId]
            ])->first();
        return response()->json([
                'status'=>200,
                'variant'=>$variant
            ]);
    }

    public function updateVariant(Request $request, $productId, $variantId){
        $messages = [
            'variant_name.required'             =>   "Product name is required.",
            'variant_measurement.required'      =>   "Brand name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'variant_name'          => 'required',
            'variant_measurement'   => 'required',
        ], $messages);

        if ($validator->passes()) {
            $variant = Variant::where([
                ['id','!=',$variantId],
                ['variant_name',$request->variant_name],
                ['product_id',$productId],
                ])->get();
            if($variant->isEmpty()){
                $variant = Variant::find($variantId);
                $variant->variant_name          =$request->variant_name;
                $variant->variant_measurement   =$request->variant_measurement;
                $variant->variant_description   =$request->variant_description;
                // $variant->available_discount    =$request->available_discount;
                // $variant->discount_type         =$request->discount_type;
                // $variant->discount              =$request->discount;
                // $variant->taxName               =$request->taxName;
                // $variant->isExcludedTax         =$request->isExcludedTax;
                // $variant->tax                   =$request->tax;
                $variant->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Variant Updated Successfully'
                ]);
            }
            else{
                return response()->json([
                    'status'=>400,
                    'message'=>'Variant Exist'
                ]);
            }
        }
        else{
            return response()->json([
                'status'=>400,
                'message'=>'Fillup Required Fields'
            ]);
        }
    }

    public function deleteVariant($productId, $variantId){
        $variant = Variant::where([
            ['id',$variantId],
            ['product_id',$productId]
            ])->delete();
        return response()->json([
                'status'=>200,
                'message'=>'Variant Delete Successfull'
            ]);
    }
}
