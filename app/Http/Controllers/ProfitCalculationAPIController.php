<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProfitCalculationService;

class ProfitCalculationAPIController extends Controller
{
    public function data($subscriberId, $storeId){
        return (new ProfitCalculationService)->data($subscriberId, $storeId);
    }

    public function loadData(Request $request, $subscriberId, $storeId){
        return (new ProfitCalculationService)->loadData($request, $subscriberId, $storeId);
    }
}
