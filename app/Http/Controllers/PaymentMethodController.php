<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function list($subscriberId){
        $paymentMethod = PaymentMethod::where('subscriber_id', $subscriberId)->get();

        return response() -> json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $paymentMethod,
        ]);
    }
}
