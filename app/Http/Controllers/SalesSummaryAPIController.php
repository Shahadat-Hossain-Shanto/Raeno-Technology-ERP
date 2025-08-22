<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderedProduct;

class SalesSummaryAPIController extends Controller
{
    public function salesSummary(Request $request, $id){

        //PAUSE
        //$id is here store id getting from url

        // $sales = Order::join('ordered_products', 'orders.id', '=', 'ordered_products.orderId')
        //             ->where([
        //                 ['orders.subscriber_id', 1],
        //                 ['orders.store_id', $id],
                        
        //             ])
        //             ->get();

        // return $sales;
    }
}
