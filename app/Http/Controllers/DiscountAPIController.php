<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\StoreDiscount;

class DiscountAPIController extends Controller
{
    public function list(Request $request, $subscriberId, $storeId){

        $discount = StoreDiscount::where([['subscriber_id',  $subscriberId], ['storeId', $storeId]])->get();

        return response() -> json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $discount,
            
        ]);  

    }
}
