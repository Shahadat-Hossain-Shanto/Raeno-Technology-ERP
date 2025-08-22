<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductInHistory;

class ProductInReportDetailsController extends Controller
{
    public function index(Request $request, $storeId, $productId, $variantId){

        $details = ProductInHistory::where([
                    ['store', '=', $storeId],
                    ['product', '=', $productId],
                    ['variant_id', '=', $variantId]
                ])->get();

        foreach($details as $data){
            $store_name = $data->store_name;
            $product_name = $data->product_name;
            $variant_name = $data->variant_name;
        }

        return view('report/product-in-report-details', ['details' => $details, 'store_name' => $store_name, 'product_name' => $product_name, 'variant_name' => $variant_name ]);
    }
}
