<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;
use App\Models\StoreInventory;
use App\Models\ProductInHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductTransferHistory;


class ProfitCalculationController extends Controller
{
    public function index(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('report/profit-calculation-report', ['stores' => $stores]);
    }
    public function data($storeId){
        $subscriber_id = Auth::user()->subscriber_id;
        if($storeId==0){
            $stores=Store::where('subscriber_id',$subscriber_id)->get();
            // Total Stock
            $storeStockAmount=0;
            foreach($stores AS $store){
                $storeStocks= StoreInventory::where('store_id', $store->id)->get();
                foreach($storeStocks AS $storeStock){
                $storeStockAmount = $storeStockAmount + ($storeStock->onHand*$storeStock->price);
                }
            }

            // Purchase Stock Amount
            $purchaseAmount = DB::table('purchase_products')
            ->select(DB::raw('SUM(grandTotal) AS purchaseAmount'))
            ->where('subscriber_id', $subscriber_id)
            ->get();

            // Product In Amount
            $productInAmount=0;
            $productInStocks = ProductInHistory::where('subscriber_id', $subscriber_id)->get();
            foreach($productInStocks AS $productInStock){
                $productInAmount = $productInAmount + ($productInStock->quantity*$productInStock->unit_price);
            }

            // Product Transfer Into Amount
            $productTransferIntoAmount=0;
            // $productTransferStocks = ProductTransferHistory::where('subscriber_id', $subscriber_id)->get();
            // foreach($productTransferStocks AS $productTransferStock){
            //     $productTransferAmount = $productTransferAmount + ($productTransferStock->quantity*$productTransferStock->price);
            // }

            // Product Transfer From Amount
            $productTransferFromAmount=0;
            // $productTransferStocks = ProductTransferHistory::where('subscriber_id', $subscriber_id)->get();
            // foreach($productTransferStocks AS $productTransferStock){
            //     $productTransferAmount = $productTransferAmount + ($productTransferStock->quantity*$productTransferStock->price);
            // }

            // Company Return
            $returnAmount = DB::table('purchase_returns')
            ->select(DB::raw('SUM(net_return) AS returnAmount'))
            ->where('subscriber_id', $subscriber_id)
            ->get();

            // Selling Cost Amount
            $purchaseProducts = DB::table('ordered_products')
            ->select('ordered_products.productId','ordered_products.variant_id','ordered_products.quantity')
            ->where('subscriber_id', $subscriber_id)
            ->get();
            // log::info($purchaseProducts);
            $sellingCostAmount=0;
            foreach($purchaseProducts as $purchaseProduct)
            {
                $count=0;
                foreach($stores AS $store){
                    $productPrice=DB::table('store_inventories')
                    ->select('productId','price')
                    ->where([
                        ['productId',$purchaseProduct->productId],
                        ['variant_id',$purchaseProduct->variant_id],
                        ['store_id',$store->id],
                    ])
                    ->orderByDesc('created_at')->first();
                    if($count==0){
                        if($productPrice){
                            $count=1;
                            $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$productPrice->price);
                        }
                    }
                }
            }

            // Total Return Amount & Return Cost Amount
            $returnCostAmount = DB::table('sales_returns')
            ->select(DB::raw('SUM(net_return) AS returnCostAmount'))
            ->where('subscriber_id', $subscriber_id)->get();

            // Total Sell Amount
            $totalSellAmount = DB::table('orders')
            ->select(DB::raw('SUM(total) AS totalSellAmount'))
            ->where('subscriber_id', $subscriber_id)->get();

            // Total Vat Amount
            $totalVatAmount = DB::table('orders')
            ->select(DB::raw('SUM(totalTax) AS totalVatAmount'))
            ->where('subscriber_id', $subscriber_id)->get();

            // Total Discount Amount
            $totalDiscountAmount = DB::table('orders')
            ->select(DB::raw('SUM(totalDiscount) AS totalDiscountAmount'))
            ->where('subscriber_id', $subscriber_id)->get();

            // Total Special Discount Amount
            $totalSpecialDiscountAmount = DB::table('orders')
            ->select(DB::raw('SUM(specialDiscount) AS totalSpecialDiscountAmount'))
            ->where('subscriber_id', $subscriber_id)->get();

            return response()->json([
                'status' => 200,
                // 'storeStockAmount' => $storeStockAmount,
                'purchaseAmount' => $purchaseAmount[0]->purchaseAmount,
                'productInAmount' => $productInAmount,
                'productTransferIntoAmount' => $productTransferIntoAmount,
                'productTransferFromAmount' => $productTransferFromAmount,
                'returnAmount' => $returnAmount[0]->returnAmount,
                'sellingCostAmount' => $sellingCostAmount,
                'returnCostAmount' => $returnCostAmount[0]->returnCostAmount,
                'totalSellAmount' => $totalSellAmount[0]->totalSellAmount,
                'totalVatAmount' => $totalVatAmount[0]->totalVatAmount,
                'totalDiscountAmount' => $totalDiscountAmount[0]->totalDiscountAmount,
                'totalSpecialDiscountAmount' => $totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount,
            ]);
        }
        else{
            $store=Store::find($storeId);
            // Total Stock
            $storeStockAmount=0;
            $storeStocks= StoreInventory::where('store_id', $storeId)->get();
            foreach($storeStocks AS $storeStock){
            $storeStockAmount = $storeStockAmount + ($storeStock->onHand*$storeStock->price);
            }

            // Purchase Stock Amount
            $purchaseAmount = DB::table('purchase_products')
            ->select(DB::raw('SUM(grandTotal) AS purchaseAmount'))
            ->where([['subscriber_id', $subscriber_id],['store', $store->store_name]])
            ->get();

            // Product In Amount
            $productInAmount=0;
            $productInStocks = ProductInHistory::where([['subscriber_id', $subscriber_id],['store', $store->id]])->get();
            foreach($productInStocks AS $productInStock){
                $productInAmount = $productInAmount + ($productInStock->quantity*$productInStock->unit_price);
            }

            // Product Transfer Into Amount
            $productTransferIntoAmount=0;
            $productTransferIntoStocks = ProductTransferHistory::where([['subscriber_id', $subscriber_id],['to_store', $store->id]])->get();
            foreach($productTransferIntoStocks AS $productTransferIntoStock){
                $productTransferIntoAmount = $productTransferIntoAmount + ($productTransferIntoStock->quantity*$productTransferIntoStock->price);
            }

            // Product Transfer From Amount
            $productTransferFromAmount=0;
            $productTransferFromStocks = ProductTransferHistory::where([['subscriber_id', $subscriber_id],['from_store', $store->id]])->get();
            foreach($productTransferFromStocks AS $productTransferFromStock){
                $productTransferFromAmount = $productTransferFromAmount + ($productTransferFromStock->quantity*$productTransferFromStock->price);
            }

            // Company Return
            $returnAmount = DB::table('purchase_returns')
            ->select(DB::raw('SUM(net_return) AS returnAmount'))
            ->where([['subscriber_id', $subscriber_id],['store_id', $store->id]])
            ->get();

            // Selling Cost Amount
            $sellingCostAmount=0;
            $orders=Order::where('store_id', $store->id)->get();
            foreach($orders AS $order)
            {
                $purchaseProducts=OrderedProduct::where('orderId', $order->id)->get();
                foreach($purchaseProducts as $purchaseProduct)
                {
                    $count=0;
                    $productPrice=DB::table('store_inventories')
                    ->select('productId','price')
                    ->where([
                        ['productId',$purchaseProduct->productId],
                        ['variant_id',$purchaseProduct->variant_id],
                        ['store_id',$store->id],
                    ])
                    ->orderByDesc('created_at')->first();
                    if($count==0){
                        if($productPrice){
                            $count=1;
                            $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$productPrice->price);
                        }
                    }
                }
            }

            // Total Return Amount & Return Cost Amount
            $returnCostAmount = DB::table('sales_returns')
            ->select(DB::raw('SUM(net_return) AS returnCostAmount'))
            ->where([['subscriber_id', $subscriber_id],['store_id',$store->id]])->get();

            // Total Sell Amount
            $totalSellAmount = DB::table('orders')
            ->select(DB::raw('SUM(total) AS totalSellAmount'))
            ->where('store_id', $store->id)->get();

            // Total Vat Amount
            $totalVatAmount = DB::table('orders')
            ->select(DB::raw('SUM(totalTax) AS totalVatAmount'))
            ->where('store_id', $store->id)->get();

            // Total Discount Amount
            $totalDiscountAmount = DB::table('orders')
            ->select(DB::raw('SUM(totalDiscount) AS totalDiscountAmount'))
            ->where('store_id', $store->id)->get();

            // Total Special Discount Amount
            $totalSpecialDiscountAmount = DB::table('orders')
            ->select(DB::raw('SUM(specialDiscount) AS totalSpecialDiscountAmount'))
            ->where('store_id', $store->id)->get();

            return response()->json([
                'status' => 200,
                // 'storeStockAmount' => $storeStockAmount,
                'purchaseAmount' => $purchaseAmount[0]->purchaseAmount,
                'productInAmount' => $productInAmount,
                'productTransferIntoAmount' => $productTransferIntoAmount,
                'productTransferFromAmount' => $productTransferFromAmount,
                'returnAmount' => $returnAmount[0]->returnAmount,
                'sellingCostAmount' => $sellingCostAmount,
                'returnCostAmount' => $returnCostAmount[0]->returnCostAmount,
                'totalSellAmount' => $totalSellAmount[0]->totalSellAmount,
                'totalVatAmount' => $totalVatAmount[0]->totalVatAmount,
                'totalDiscountAmount' => $totalDiscountAmount[0]->totalDiscountAmount,
                'totalSpecialDiscountAmount' => $totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount,
            ]);
        }
    }

    public function loadData(Request $request){
        if(!empty($request->startdate) && !empty($request->enddate)){
            $from = date($request->startdate). ' 00:00:00';
            $to = date($request->enddate). ' 23:59:59';
            $subscriber_id = Auth::user()->subscriber_id;

            if($request->store==0){
                $stores=Store::where('subscriber_id',$subscriber_id)->get();

                // Selling Cost Amount
                $purchaseProducts = DB::table('ordered_products')
                ->select('ordered_products.productId','ordered_products.variant_id','ordered_products.quantity')
                ->whereBetween(DB::raw('DATE(ordered_products.created_at)'), [$from, $to])
                ->where('subscriber_id', $subscriber_id)
                ->get();
                // log::info($purchaseProducts);
                $sellingCostAmount=0;
                foreach($purchaseProducts as $purchaseProduct)
                {
                    $count=0;
                    foreach($stores AS $store){
                        $productPrice=DB::table('store_inventories')
                        ->select('productId','price')
                        ->where([
                            ['productId',$purchaseProduct->productId],
                            ['variant_id',$purchaseProduct->variant_id],
                            ['store_id',$store->id],
                        ])
                        ->orderByDesc('created_at')->first();
                        // log::info(json_encode($productPrice));
                        if($count==0){
                            if($productPrice){
                                $count=1;
                                $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$productPrice->price);
                            }
                        }
                    }
                }

                // Total Return Amount & Return Cost Amount
                $returnCostAmount = DB::table('sales_returns')
                ->select(DB::raw('SUM(net_return) AS returnCostAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('subscriber_id', $subscriber_id)->get();

                // Total Sell Amount
                $totalSellAmount = DB::table('orders')
                ->select(DB::raw('SUM(total) AS totalSellAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('subscriber_id', $subscriber_id)->get();

                // Total Vat Amount
                $totalVatAmount = DB::table('orders')
                ->select(DB::raw('SUM(totalTax) AS totalVatAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('subscriber_id', $subscriber_id)->get();

                // Total Discount Amount
                $totalDiscountAmount = DB::table('orders')
                ->select(DB::raw('SUM(totalDiscount) AS totalDiscountAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('subscriber_id', $subscriber_id)->get();

                // Total Discount Amount
                $totalSpecialDiscountAmount = DB::table('orders')
                ->select(DB::raw('SUM(specialDiscount) AS totalSpecialDiscountAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('subscriber_id', $subscriber_id)->get();

                return response()->json([
                    'status' => 200,
                    'sellingCostAmount' => $sellingCostAmount,
                    'returnCostAmount' => $returnCostAmount[0]->returnCostAmount,
                    'totalSellAmount' => $totalSellAmount[0]->totalSellAmount,
                    'totalVatAmount' => $totalVatAmount[0]->totalVatAmount,
                    'totalDiscountAmount' => $totalDiscountAmount[0]->totalDiscountAmount,
                    'totalSpecialDiscountAmount' => $totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount,
                ]);
            }
            else{
                $store=Store::find($request->store);
                // Selling Cost Amount
                $sellingCostAmount=0;
                $orders=Order::where('store_id', $store->id)->whereBetween(DB::raw('DATE(orders.created_at)'), [$from, $to])
                ->get();
                foreach($orders AS $order)
                {
                    $purchaseProducts=OrderedProduct::where('orderId', $order->id)->get();
                    foreach($purchaseProducts as $purchaseProduct)
                    {
                        $count=0;
                        $productPrice=DB::table('store_inventories')
                        ->select('productId','price')
                        ->where([
                            ['productId',$purchaseProduct->productId],
                            ['variant_id',$purchaseProduct->variant_id],
                            ['store_id',$store->id],
                        ])
                        ->whereBetween(DB::raw('DATE(store_inventories.created_at)'), [$from, $to])
                        ->orderByDesc('created_at')->first();
                        if($count==0){
                            if($productPrice){
                                $count=1;
                                $sellingCostAmount=$sellingCostAmount+($purchaseProduct->quantity*$productPrice->price);
                            }
                        }
                    }
                }

                // Total Return Amount & Return Cost Amount
                $returnCostAmount = DB::table('sales_returns')
                ->select(DB::raw('SUM(net_return) AS returnCostAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where([['subscriber_id', $subscriber_id],['store_id',$store->id]])->get();

                // Total Sell Amount
                $totalSellAmount = DB::table('orders')
                ->select(DB::raw('SUM(total) AS totalSellAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('store_id', $store->id)->get();

                // Total Vat Amount
                $totalVatAmount = DB::table('orders')
                ->select(DB::raw('SUM(totalTax) AS totalVatAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('store_id', $store->id)->get();

                // Total Discount Amount
                $totalDiscountAmount = DB::table('orders')
                ->select(DB::raw('SUM(totalDiscount) AS totalDiscountAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('store_id', $store->id)->get();

                // Total Special Discount Amount
                $totalSpecialDiscountAmount = DB::table('orders')
                ->select(DB::raw('SUM(specialDiscount) AS totalSpecialDiscountAmount'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->where('store_id', $store->id)->get();

                return response()->json([
                    'status' => 200,
                    'sellingCostAmount' => $sellingCostAmount,
                    'returnCostAmount' => $returnCostAmount[0]->returnCostAmount,
                    'totalSellAmount' => $totalSellAmount[0]->totalSellAmount,
                    'totalVatAmount' => $totalVatAmount[0]->totalVatAmount,
                    'totalDiscountAmount' => $totalDiscountAmount[0]->totalDiscountAmount,
                    'totalSpecialDiscountAmount' => $totalSpecialDiscountAmount[0]->totalSpecialDiscountAmount,
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 400
            ]);
        }
    }
}
