<?php

namespace App\Http\Services;

use Log;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\DB;

class ProfitCalculationService
{
    public function data($subscriberId, $storeId){
        $storeName=Store::find($storeId)->store_name;
        // Total Stock Balance
        $storeStocks= StoreInventory::where('store_id', $storeId)->get();
        $stockAmount=0;
        foreach($storeStocks AS $storeStock){
            $stockAmount = $stockAmount + ($storeStock->productIncoming*$storeStock->price);
        }

        // // Opening Stock Balance
        // $yesterdayDate = Carbon::yesterday();
        // $startDate = '2023-01-01 00:00:00';
        // $endDate = $yesterdayDate. ' 23:59:59';
        // $openingStockAmount = DB::table('purchase_products')
        // ->select(DB::raw('SUM(grandTotal) AS openingStockAmount'))
        // ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
        // ->where([
        //     ['subscriber_id', $subscriberId],
        //     ['store', $storeName]
        //     ])
        // ->get();

        // // Today's Purchase Balance
        // $todayDate = Carbon::today();
        // $todayStart = $todayDate. ' 00:00:00';
        // $todayEnd = $todayDate. ' 23:59:59';
        // $purchaseAmount = DB::table('purchase_products')
        // ->select(DB::raw('SUM(grandTotal) AS purchaseAmount'))
        // ->whereBetween(DB::raw('DATE(created_at)'), [$todayStart, $todayEnd])
        // ->where([
        //     ['subscriber_id', $subscriberId],
        //     ['store', $storeName]
        //     ])
        // ->get();

        // Company Return
        $returnAmount = DB::table('purchase_returns')
        ->select(DB::raw('SUM(net_return) AS returnAmount'))
        ->where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
            ])
        ->get();

        $orders=Order::where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
        ])
        ->get();
        // Selling Cost Amount
        $sellingCostAmount=0;

        foreach($orders as $order)
        {
            $purchaseProducts = DB::table('ordered_products')
            ->select('ordered_products.productId','ordered_products.quantity','ordered_products.totalPrice','ordered_products.quantity')
            ->where('orderId', $order->id)
            ->get();
            foreach($purchaseProducts as $purchaseProduct)
            {
                $productPrice=DB::table('store_inventories')
                ->select('productId','price')
                ->where([
                    ['store_id',$storeId],
                    ['productId',$purchaseProduct->productId],
                ])
                ->orderByDesc('created_at')->first();
                if($productPrice){
                    $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$productPrice->price);
                }
                else{
                    $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*($purchaseProduct->totalPrice/$purchaseProduct->quantity));
                }
            }
        }

        // Total Return Amount & Return Cost Amount
        $returnCostAmount = DB::table('sales_returns')
        ->select(DB::raw('SUM(net_return) AS returnCostAmount'))
        ->where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
        ])
        ->get();

        // Total Sell Amount
        $totalSellAmount = DB::table('orders')
        ->select(DB::raw('SUM(total) AS totalSellAmount'))
        ->where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
        ])
        ->get();

        // Total Vat Amount
        $totalVatAmount = DB::table('orders')
        ->select(DB::raw('SUM(totalTax) AS totalVatAmount'))
        ->where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
        ])
        ->get();

        // Total Discount Amount
        $totalDiscountAmount = DB::table('orders')
        ->select(DB::raw('SUM(totalDiscount) AS totalDiscountAmount'))
        ->where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
        ])
        ->get();

        // Total Special Discount Amount
        $totalSpecialDiscountAmount = DB::table('orders')
        ->select(DB::raw('SUM(specialDiscount) AS totalSpecialDiscountAmount'))
        ->where([
            ['subscriber_id', $subscriberId],
            ['store_id', $storeId]
        ])
        ->get();

        $sellingCostAmount = floatval($sellingCostAmount);
        $returnCostAmount = floatval($returnCostAmount[0]->returnCostAmount);
        $totalSellingCostAmount = floatval($sellingCostAmount-$returnCostAmount);

        $stockAmount = floatval($stockAmount);
        $transactionAmount = floatval($totalSellingCostAmount);
        $returnAmount_purchase = floatval($returnAmount[0]->returnAmount);
        $closingStockAmount = ($stockAmount)-($transactionAmount)-($returnAmount_purchase);

        $sellAmount = floatval($totalSellAmount[0]->totalSellAmount);
        $vatAmount = floatval($totalVatAmount[0]->totalVatAmount);
        $discountAmount = floatval($totalDiscountAmount[0]->totalDiscountAmount);
        $specialDiscountAmount = floatval($totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount);
        $returnAmount_sales = floatval($returnCostAmount);
        $netSellAmount = floatval($sellAmount+$vatAmount-$discountAmount-$specialDiscountAmount-$returnAmount_sales);

        $netProdit = floatval($netSellAmount - $totalSellingCostAmount);

        return response()->json([

            'sellingCostAmount' => (float)$sellingCostAmount,
            'returnCostAmount' => (float)$returnCostAmount,
            'totalSellingCostAmount' => (float)$totalSellingCostAmount,

            'stockAmount' => (float)$stockAmount,
            'transactionAmount' => (float)$transactionAmount,
            'returnAmount_purchase' => (float)$returnAmount_purchase,
            'closingStockAmount' => (float)$closingStockAmount,

            'sellAmount' => (float)$sellAmount,
            'vatAmount' => (float)$vatAmount,
            'discountAmount' => (float)$discountAmount,
            'specialDiscountAmount' => (float)$specialDiscountAmount,
            'returnAmount_sales' => (float)$returnAmount_sales,
            'netSellAmount' => (float)$netSellAmount,

            'netProdit' => (float)$netProdit,
        ]);
    }



    public function loadData(Request $request, $subscriberId, $storeId){
        $storeName=Store::find($storeId)->store_name;
        if(!empty($request->startdate) && !empty($request->enddate)){
            $from = date($request->startdate). ' 00:00:00';
            $to = date($request->enddate). ' 23:59:59';

            // Opening Stock Balance

            // Purchase Balance
            $purchaseAmount = DB::table('purchase_products')
            ->select(DB::raw('SUM(grandTotal) AS purchaseAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store', $storeName]
                ])

            ->get();

            // Company Return
            $returnAmount = DB::table('purchase_returns')
            ->select(DB::raw('SUM(net_return) AS returnAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
                ])

            ->get();

            $orders=Order::where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();
            // Selling Cost Amount
            $sellingCostAmount=0;

            foreach($orders as $order)
            {
                $purchaseProducts = DB::table('ordered_products')
                ->select('ordered_products.productId','ordered_products.quantity','ordered_products.price')
                ->whereBetween(DB::raw('DATE(ordered_products.created_at)'), [$from, $to])
                ->where('orderId', $order->id)
                ->get();
                // log::info($purchaseProducts);
                $sellingCostAmount=0;
                foreach($purchaseProducts as $purchaseProduct)
                {
                    $productPrice=DB::table('store_inventories')
                    ->select('productId','price')
                    ->where([
                        ['store_id',$storeId],
                        ['productId',$purchaseProduct->productId],
                        ['mrp',$purchaseProduct->price],
                    ])
                    ->orderByDesc('created_at')->first();
                    // info(json_encode($productPrice));
                    if($productPrice){
                        $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$productPrice->price);
                    }
                    else{
                        $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$purchaseProduct->price);
                    }
                }
            }

            // Total Return Amount & Return Cost Amount
            $returnCostAmount = DB::table('sales_returns')
            ->select(DB::raw('SUM(net_return) AS returnCostAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();

            // Total Sell Amount
            $totalSellAmount = DB::table('orders')
            ->select(DB::raw('SUM(total) AS totalSellAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();

            // Total Vat Amount
            $totalVatAmount = DB::table('orders')
            ->select(DB::raw('SUM(totalTax) AS totalVatAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();

            // Total Discount Amount
            $totalDiscountAmount = DB::table('orders')
            ->select(DB::raw('SUM(totalDiscount) AS totalDiscountAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();

            // Total Discount Amount
            $totalSpecialDiscountAmount = DB::table('orders')
            ->select(DB::raw('SUM(specialDiscount) AS totalSpecialDiscountAmount'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where([
                ['subscriber_id', $subscriberId],
                ['store_id', $storeId]
            ])
            ->get();

            $openingStockAmount = 0;;
            $purchaseAmount = floatval($purchaseAmount[0]->purchaseAmount);
            $totalStockAmount = floatval($openingStockAmount+ $purchaseAmount);

            $sellingCostAmount = floatval($sellingCostAmount);
            $returnCostAmount = floatval($returnCostAmount[0]->returnCostAmount);
            $totalSellingCostAmount = floatval($sellingCostAmount+$returnCostAmount);

            $stockAmount = floatval($totalStockAmount);
            $transactionAmount = floatval($totalSellingCostAmount);
            $returnAmount_purchase = floatval($returnAmount[0]->returnAmount);
            $closingStockAmount = ($stockAmount)+($transactionAmount)+($returnAmount_purchase);

            $sellAmount = floatval($totalSellAmount[0]->totalSellAmount);
            $vatAmount = floatval($totalVatAmount[0]->totalVatAmount);
            $discountAmount = floatval($totalDiscountAmount[0]->totalDiscountAmount);
            $specialDiscountAmount = floatval($totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount);
            $returnAmount_sales = floatval($returnCostAmount);
            $netSellAmount = floatval($sellAmount+$vatAmount-$discountAmount-$specialDiscountAmount-$returnAmount_sales);

            $netProdit = floatval($netSellAmount - $totalSellingCostAmount);

            return response()->json([
                'startDate' => $request->startdate,
                'endDate' => $request->enddate,
                'openingStockAmount' => (float)$openingStockAmount,
                'purchaseAmount' => (float)$purchaseAmount,
                'totalStockAmount' => (float)$totalStockAmount,

                'sellingCostAmount' => (float)$sellingCostAmount,
                'returnCostAmount' => (float)$returnCostAmount,
                'totalSellingCostAmount' => (float)$totalSellingCostAmount,

                'stockAmount' => (float)$stockAmount,
                'transactionAmount' => (float)$transactionAmount,
                'returnAmount_purchase' => (float)$returnAmount_purchase,
                'closingStockAmount' => (float)$closingStockAmount,

                'sellAmount' => (float)$sellAmount,
                'vatAmount' => (float)$vatAmount,
                'discountAmount' => (float)$discountAmount,
                'specialDiscountAmount' => (float)$specialDiscountAmount,
                'returnAmount_sales' => (float)$returnAmount_sales,
                'netSellAmount' => (float)$netSellAmount,

                'netProdit' => (float)$netProdit,
            ]);
            // return response()->json([
            //     'status' => 200,
            //     'openingStockAmount' => $openingStockAmount[0]->openingStockAmount,
            //     'purchaseAmount' => $purchaseAmount[0]->purchaseAmount,
            //     'returnAmount' => $returnAmount[0]->returnAmount,
            //     'sellingCostAmount' => $sellingCostAmount,
            //     'returnCostAmount' => $returnCostAmount[0]->returnCostAmount,
            //     'totalSellAmount' => $totalSellAmount[0]->totalSellAmount,
            //     'totalVatAmount' => $totalVatAmount[0]->totalVatAmount,
            //     'totalDiscountAmount' => $totalDiscountAmount[0]->totalDiscountAmount,
            //     'totalSpecialDiscountAmount' => $totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount,
            // ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'error' => 'Enter From & To Date'
            ]);
        }
    }
}
