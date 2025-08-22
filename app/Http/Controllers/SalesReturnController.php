<?php

namespace App\Http\Controllers;

use DB;

use Log;
use App\Models\Order;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\SalesReturn;

use Illuminate\Http\Request;
use App\Models\OrderedProduct;

use App\Models\StoreInventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalesReturnController extends Controller
{
    public function create(){
        $orders = Order::where([['subscriber_id', Auth::user()->subscriber_id]])->get()->unique('orderId');
        return view('sales-return/sales-return-add', ['orders' => $orders]);
    }

    public function search(Request $request, $invoiceno){
        $order=Order::where([
            ['orders.orderId', $invoiceno],
            ['orders.subscriber_id', Auth::user()->subscriber_id]
        ])
        ->get();
        // log::alert($order);
        $data = Order::join('ordered_products', 'orders.id', 'ordered_products.orderId')
        ->where([
                ['orders.orderId', $invoiceno],
                ['orders.subscriber_id', Auth::user()->subscriber_id]
            ])
        ->get();
        // log::alert($data);

        $data2 = Order::join('ordered_products', 'orders.id', 'ordered_products.orderId')
        ->join('products', 'ordered_products.productId', 'products.id')
        ->where([
                ['orders.orderId', $invoiceno],
                ['orders.subscriber_id', Auth::user()->subscriber_id]
            ])
        ->select('products.freeItemName', 'products.requiredQuantity', 'products.freeQuantity', 'products.productName', 'products.offerItemId')
        ->get();

        if($data->isEmpty()){
            return response()->json([
                'status' => 200,
                'message' => "Invoice no. doesn't exist!!!"
            ]);
        }

        foreach($data as $item){
            $clientId = $item->clientId;
        }

        if($clientId != 0){
            $client = Client::where('id', $clientId)->first();
            $clientName = $client->name;
            $clientId = $client->id;

            return response()->json([
                'status' => 200,
                'data'=> $data,
                'data2'=> $data2,
                'clientMobile' => $client->mobile,
                'clientName'=> $clientName,
                'clientId'=> $clientId,
                'order'=> $order,
                'message' => 'Success'
            ]);
        }

        return response()->json([
                'status' => 200,
                'data'=> $data,
                'data2'=> $data2,
                'order'=> $order,
                'message' => 'Success'
            ]);

    }

    public function detailsView($orderId){
        return view('sales-return/sales-return-details');
    }

    public function store(Request $request){
        // Log::info($request);
            $checkReturn = SalesReturn::where([
                    ['invoice_no', $request->invoiceNo],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
            ->get();
            $order = Order::where('orderId', $request->invoiceNo)->first();

            if($checkReturn->isEmpty()){
                foreach($request->productList as $product){
                    $returnDiscount = 0;
                    $returnVat = 0;
                    $returnPrice = 0;
                    // Log::info('Ok');
                    if(doubleval($product['returnQty']) > 0){
                    $returnDiscount = $returnDiscount + doubleval($product['returnDiscount']);
                    $returnVat =  $returnVat + doubleval($product['returnTax']);
                    $returnPrice =  $returnPrice + doubleval($product['returnPrice']);
                        $sales_return = new SalesReturn;
                        $sales_return->invoice_no = $request->invoiceNo;
                        $sales_return->net_return = doubleval($request->netReturn);
                        $sales_return->total_tax_return = doubleval($request->totalTax);
                        $sales_return->total_deduction = doubleval($request->totalDeduction);
                        $sales_return->product_id = (int)$product['productId'];
                        $sales_return->product_name = $product['productName'];
                        $sales_return->return_qty = doubleval($product['returnQty']);
                        $sales_return->price = doubleval($product['price']);
                        $sales_return->tax_return = doubleval($product['taxAmount']);
                        $sales_return->deduction = doubleval($product['deductionAmount']);
                        $sales_return->note = $request->note;
                        $sales_return->subscriber_id = Auth::user()->subscriber_id;
                        $sales_return->user_id = Auth::user()->id;
                        $sales_return->store_id = (int)$request->storeId;
                        $sales_return->return_number = $request->returnNumber;

                        // Log::info($request->storeId);
                        // Log::info($request->productId);
                        // Log::info($request->variantId);

                        // $storeId = (int)$request->storeId;
                        // $productId = (int)$product['productId'];
                        if((int)$product['productId']!=0)
                        {
                            if($request->storeId==0)
                            {
                                $inventories = Inventory::where([
                                    ['productId', '=', (int)$product['productId']],
                                    ['variant_id', '=', (int)$product['variantId']]
                                ])
                                ->orderBy('created_at', 'desc')
                                ->first();
                                $onHand = $inventories->onHand;
                                $productOutgoing = $inventories->productOutgoing;
                                $inventory = Inventory::find($inventories->id);
                                $inventory->onHand = $onHand + doubleval($product['returnQty']);
                                $inventory->productOutgoing = $productOutgoing - doubleval($product['returnQty']);

                                $inventory->save();
                            }
                            else
                            {
                                $store_inventories = StoreInventory::where([
                                                    ['store_id', '=', $request->storeId],
                                                    ['productId', '=', (int)$product['productId']],
                                                    ['variant_id', '=', (int)$product['variantId']]
                                                ])
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                $onHand = $store_inventories->onHand;
                                $productOutgoing = $store_inventories->productOutgoing;
                                $store_inventory = StoreInventory::find($store_inventories->id);
                                $store_inventory->onHand = $onHand + doubleval($product['returnQty']);
                                $store_inventory->productOutgoing = $productOutgoing - doubleval($product['returnQty']);

                                $store_inventory->save();
                            }
                        }

                        $sales_return->save();
                        //adjust return quantity and price in order and ordered proudcts
                        // $orderProduct = OrderedProduct::where([
                        //     ['orderId', $order->id],
                        //     ['productId', (int)$product['productId']],
                        //     ['variant_id', (int)$product['variantId']],
                        // ])->first();
                        // $orderedQty = $orderProduct->quantity;
                        // $orderedProduct = OrderedProduct::find($orderProduct->id);
                        // $orderX = Order::find($order->id);
                        // if(($orderedQty - doubleval($product['returnQty'])) == 0){
                        //     $orderedProduct = OrderedProduct::find($orderProduct->id)->delete($orderProduct->id);

                        //     if(($order->grandTotal - doubleval($request->netReturn)) == 0){
                        //         $orderX = Order::find($order->id);
                        //         if($orderX){
                        //             $orderX = Order::find($order->id)->delete($order->id);
                        //         }
                        //     }else{
                        //         $orderX->grandTotal = $orderX->grandTotal - doubleval($request->netReturn);
                        //         $orderX->total = $orderX->total - $returnPrice;
                        //         $orderX->save();
                        //     }
                        // }else{
                        //     $orderedProduct->grandTotal = $orderedProduct->grandTotal - doubleval($request->netReturn);
                        //     $orderedProduct->totalPrice = $orderedProduct->totalPrice - $returnPrice;
                        //     $orderedProduct->quantity = $orderedProduct->quantity - doubleval($product['returnQty']);
                        // $orderedProduct->totalDiscount   = $orderedProduct->totalDiscount  - $returnDiscount;
                        // $orderedProduct->totalTax = $orderedProduct->totalTax - $returnVat;

                        //     $orderX->grandTotal = $order->grandTotal - doubleval($request->netReturn);
                        //     $orderX->total = $orderX->total - $returnPrice;
                        //     $orderX->totalDiscount  = $order->totalDiscount - $returnDiscount;
                        //     $orderX->totalTax = $order->totalTax - $returnVat;
                        //     $orderX->save();
                        //     $orderedProduct->save();
                        // }
                        // Log::info($orderX);


                    }

                }

                return response() -> json([
                    'status'=>200,
                    'message' => 'Return successfully.'
                ]);

            }else{
                return response() -> json([
                    'status'=>200,
                    'message' => 'Already returned.'
                ]);
            }
    }

    public function listView(){
        return view('sales-return/sales-return-list');
    }

    public function list(Request $request){
        $data = SalesReturn::where('subscriber_id', Auth::user()->subscriber_id)->orderBy('created_at', 'desc')->get()->unique('return_number');
        // $dataX = SalesReturn::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $data = $dataX->groupBy('return_number');
        // $data = SalesReturn::crossjoin('orders', 'sales_returns.invoice_no', 'orders.orderId')
        // ->crossjoin('clients', 'orders.clientId', 'clients.id')
        // ->select('sales_returns.*', 'clients.name', 'clients.mobile')
        // ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)->get()->unique('sales_returns.return_number');

        $x = [];
        foreach($data as $item){
            $y = [
                'id' => $item->id,
                'invoice_no' => $item->invoice_no,
                'net_return' => $item->net_return,
                'total_tax_return' => $item->total_tax_return,
                'total_deduction' => $item->total_deduction,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'return_qty' => $item->return_qty,
                'price' => $item->price,
                'tax_return' => $item->tax_return,
                'deduction' => $item->deduction,
                'note' => $item->note,
                'subscriber_id' => $item->subscriber_id,
                'user_id' => $item->user_id,
                'store_id' => $item->store_id,
                'return_number' => $item->return_number,

            ];

            $x[] = $y;
        }

        if($request -> ajax()){
            return response()->json([
                'data'=>$x,
                'message'=>'Success'
            ]);
        }
    }

    public function details(Request $request, $returnNumber){
        // $data = SalesReturn::where([['return_number', $returnNumber],['subscriber_id', Auth::user()->subscriber_id]])->first();

        $returnProducts = SalesReturn::where([['return_number', $returnNumber],['subscriber_id', Auth::user()->subscriber_id]])->get();
        // log::info($returnProducts);

        $data = Order::join('sales_returns', 'orders.orderId', 'sales_returns.invoice_no')
        ->leftJoin('clients', 'orders.clientId', 'clients.id')
        ->select('sales_returns.*', 'clients.name', 'clients.mobile')
        ->where([
            ['sales_returns.return_number', $returnNumber],
            ['sales_returns.subscriber_id', Auth::user()->subscriber_id]
            ])
        ->first();

        // log::info($data);
        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'returnProducts'=>$returnProducts,
            ]);
        }

        return view('sales-return/sales-return-details', ['data' => $data]);
        // return View::make('sales-return.sales-return-details', compact('data'))->render();
    }
}
