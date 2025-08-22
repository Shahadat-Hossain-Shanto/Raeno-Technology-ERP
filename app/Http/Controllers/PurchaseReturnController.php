<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\StoreInventory;

use App\Models\PurchaseProduct;
use App\Models\PurchaseProductList;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnController extends Controller
{
    public function create(){

        $purchaseOrders = PurchaseProduct::where([['status', 'received'], ['subscriber_id', Auth::user()->subscriber_id]])->get();

        return view('purchase-return/purchase-return-add', ['purchaseOrders' => $purchaseOrders]);
    }

    public function search(Request $request, $poNo){

        $data = PurchaseProduct::join('purchase_product_lists', 'purchase_products.id', 'purchase_product_lists.purchaseProductId')
        ->where([
                ['purchase_products.poNumber', $poNo],
                ['purchase_products.subscriber_id', Auth::user()->subscriber_id]
            ])
        ->get();

        // Log::info($data);

        if(!$data->isEmpty()){
            foreach($data as $item){
                $supplierId = $item->supplierId;
                $storeName = $item->store;
                $discount = $item->discount;
                $other_cost = $item->other_cost;
            }

            $store = Store::where([['store_name', $storeName], ['subscriber_id', Auth::user()->subscriber_id]])->first();
            // Log::info($store);
            if($store){
                $storeId = $store->id;
            }else{
                $storeId = 0;
            }
            // Log::info($storeId);



            if($supplierId != 0){
                $supplier = Supplier::where('id', $supplierId)->first();
                $supplierName = $supplier->name;
                $supplierId = $supplier->id;

                return response()->json([
                    'status' => 200,
                    'data'=> $data,
                    // 'data2'=> $data2,
                    'supplierName'=> $supplierName,
                    'supplierId'=> $supplierId,
                    'storeId'=> $storeId,
                    'discount'=> $discount,
                    'other_cost'=> $other_cost,
                    'message' => 'Success'
                ]);
            }

            return response()->json([
                'status' => 200,
                'data'=> $data,
                // 'data2'=> $data2,
                'storeId'=> $storeId,
                'discount'=> $discount,
                'other_cost'=> $other_cost,
                'message' => 'Success'
            ]);
        }else{
            return response()->json([
                'status' => 200,
                // 'data2'=> $data2,
                'message' => 'No data found.'
            ]);
        }
    }

    public function store(Request $request){
        log::alert($request);
        $checkReturn = PurchaseReturn::where([
                ['po_no', $request->poNo],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])
            ->get();

        if($checkReturn->isEmpty()){

            // $checkStatus = PurchaseProduct::where([
            //         ['poNumber', $request->poNo],
            //         ['subscriber_id', Auth::user()->subscriber_id]
            //     ])
            //     ->first();

            foreach($request->productList as $product){

                if(doubleval($product['returnQty']) > 0){

                    $purchase_return = new PurchaseReturn;
                    $purchase_return->po_no = $request->poNo;
                    $purchase_return->net_return = doubleval($request->netReturn);
                    // $purchase_return->total_tax_return = doubleval($request->totalTax);
                    $purchase_return->total_deduction = doubleval($request->totalDeduction);
                    $purchase_return->product_id = (int)$product['productId'];
                    $purchase_return->product_name = $product['productName'];
                    $purchase_return->return_qty = doubleval($product['returnQty']);
                    $purchase_return->price = doubleval($product['price']);
                    $purchase_return->other_cost = doubleval($product['otherCost']);
                    $purchase_return->deduction = doubleval($product['deductionAmount']);
                    $purchase_return->note = $request->note;
                    $purchase_return->subscriber_id = Auth::user()->subscriber_id;
                    $purchase_return->user_id = Auth::user()->id;
                    $purchase_return->store_id = (int)$request->storeId;
                    $purchase_return->return_number = $request->returnNumber;

                    if((int)$request->storeId == 0){

                        // $storeId = (int)$request->storeId;
                        $productId = (int)$product['productId'];
                        $inventories = Inventory::where([
                                    ['productId', '=', $productId]
                                ])
                                ->orderBy('created_at', 'desc')
                                ->get();

                        $boolX = 0;
                        foreach($inventories as $inventoryX){
                            $onHand = $inventoryX->onHand;
                            $productIncoming = $inventoryX->productIncoming;
                            if($onHand > 0 && $onHand >= doubleval($product['returnQty'])){
                                $inventory = Inventory::find($inventoryX->id);
                                $inventory->onHand = $onHand - doubleval($product['returnQty']);
                                $inventory->productIncoming =  $productIncoming - doubleval($product['returnQty']);
                                $boolX = 1;
                            }
                        }

                        if($boolX == 1){
                            $inventory->save();
                            $purchase_return->save();
                        }else{
                            return response()->json([
                                'status' => 200,
                                'message' => 'Low Stock.'
                            ]);
                        }

                        //  return response()->json([
                                // 'status' => 200,
                                // 'message' => 'Returned from Warehouse Successfully.'
                            // ]);
                    }else{
                        $storeId = (int)$request->storeId;
                        $productId = (int)$product['productId'];
                        $store_inventories = StoreInventory::where([
                                            ['store_id', '=', $storeId],
                                            ['productId', '=', $productId]
                                        ])
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                        // Log::info($store_inventories);

                        $boolX = 0;
                        foreach($store_inventories as $store_inventoryX){
                            $onHand = $store_inventoryX->onHand;
                            $productIncoming = $store_inventoryX->productIncoming;

                            if($onHand > 0 && $onHand >= doubleval($product['returnQty'])){
                                $store_inventory = StoreInventory::find($store_inventoryX->id);
                                $store_inventory->onHand = $onHand - doubleval($product['returnQty']);
                                $store_inventory->productIncoming =  $productIncoming - doubleval($product['returnQty']);

                                $boolX = 1;
                            }
                        }

                        if($boolX == 1){
                            $store_inventory->save();
                            $purchase_return->save();
                        }else{
                            return response()->json([
                                'status' => 200,
                                'message' => 'Low Stock.'
                            ]);
                        }
                    }
                }
            }
            return response()->json([
                    'status' => 200,
                    'message' => 'Returned Successfully.'
                ]);
        }else{
            return response()->json([
                'status' => 200,
                'message' => 'Already returned.'
            ]);
        }
    }

    public function listView(){
        return view('purchase-return/purchase-return-list');
    }

    public function list(Request $request){
        // $data = PurchaseReturn::where('subscriber_id', Auth::user()->subscriber_id)->get()->unique('return_number');
        $data = PurchaseReturn::where('subscriber_id', Auth::user()->subscriber_id)->orderBy('created_at', 'desc')->get()->unique('return_number');
        // $data = SalesReturn::crossjoin('orders', 'sales_returns.invoice_no', 'orders.orderId')
        // ->crossjoin('clients', 'orders.clientId', 'clients.id')
        // ->select('sales_returns.*', 'clients.name', 'clients.mobile')
        // ->where('sales_returns.subscriber_id', Auth::user()->subscriber_id)->get()->unique('sales_returns.return_number');
        // log::alert($data);
        $x = [];
        foreach($data as $item){
            $y = [
                'id' => $item->id,
                'po_no' => $item->po_no,
                'return_number' => $item->return_number,
                'total_deduction' => $item->total_deduction,
                'net_return' => $item->net_return,
                'note' => $item->note,

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

        $returnProducts = PurchaseReturn::where([['return_number', $returnNumber],['subscriber_id', Auth::user()->subscriber_id]])->get();

        $data = PurchaseProduct::join('purchase_returns', 'purchase_products.poNumber', 'purchase_returns.po_no')
        ->leftJoin('suppliers', 'purchase_products.supplierId', 'suppliers.id')
        ->select('purchase_returns.*', 'suppliers.name', 'suppliers.mobile')
        ->where([
            ['purchase_returns.return_number', $returnNumber],
            ['purchase_returns.subscriber_id', Auth::user()->subscriber_id]
        ])
        ->first();

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'returnProducts'=>$returnProducts,
            ]);
        }

        return view('purchase-return/purchase-return-details', ['data' => $data]);
        // return View::make('sales-return.sales-return-details', compact('data'))->render();
    }
}
