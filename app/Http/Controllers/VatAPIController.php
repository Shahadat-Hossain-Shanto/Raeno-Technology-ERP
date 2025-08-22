<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vat;

class VatAPIController extends Controller
{
    public function list(Request $request, $id){
        
        $vat = Vat::where('subscriber_id',  $id)->get();
       
        return response() -> json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $vat,
            
        ]);  

    }
}
