<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StoreInventory;
use App\Models\Pos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\File;

class StoreWisePosController extends Controller
{
    public function data(Request $request, $storeId){

        $data = Pos::join('stores', 'p_o_s.store_id', 'stores.id')
                ->where([
                    ['p_o_s.subscriber_id', Auth::user()->subscriber_id],
                    ['p_o_s.store_id', $storeId]
                ])
                ->get();
        
        
        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }
}
