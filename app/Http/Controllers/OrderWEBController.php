<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\Models\Vat;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Variant;
use App\Models\Discount;
use App\Models\HoldSale;
use App\Models\Inventory;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\OrderedProduct;
use App\Models\StoreInventory;
use App\Models\OrderedProductTax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Models\OrderedProductDiscount;

class OrderWEBController extends Controller
{
    public function store(Request $request){

        // log::alert($request);
        $order = new Order;

        $users = User::where('contact_number', $request->salesBy)
                    ->orWhere('email', $request->salesBy)
                    ->get();

        foreach($users as $user){
            $userId =  $user->id;
            $userName = $user->name;
        }

        $order->orderId         = $request->orderId;
        $order->clientId        = (int)$request->clientId;
        $order->total           = $request->total;
        $order->specialDiscount = $request->specialDiscount;
        $order->totalDiscount   = $request->totalDiscount;
        $order->grandTotal      = $request->grandTotal;
        $order->subscriber_id   = (int)$request->subscriberId;
        $order->store_id        = (int)$request->storeId;
        // $order->pos_id          = (int)$request->posId;

        $order->salesBy_id      = (int)$userId;

        $orderDateStr           = strtotime($request->orderDate);
        $order->orderDate       = date('Y-m-d', $orderDateStr);

        // $order->paymentType     = $request->paymentType;
        $order->totalTax        = $request->totalTax;


        if($request->referenceId != null || $request->referenceId != ""){
            $checkReferences = HoldSale::where('reference', $request->referenceId)->get();
            foreach($checkReferences as $checkReference){
                $findReference = HoldSale::find($checkReference->id)->delete($checkReference->id);
            }
        }


        $order->save();

            $deposit = new Deposit;
            if($request->clientId == ""){
                $deposit->balance       = 0;
            }else{
                $clientId = (int)$request->clientId;
                $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();
                if($lastBalance == null){
                    $deposit->balance       = 0 - (int)$request->grandTotal + (int)$request->deposit;
                }else{
                    $deposit->balance       = $lastBalance->balance - (int)$request->grandTotal + (int)$request->deposit;
                }
            }

            $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
            $deposit->due           = (int)$request->due;
            $deposit->deposit       = (int)$request->deposit;
            $deposit->status        = 'Web POS Payment';
            $deposit->deposit_type  = "Mixed";
            $deposit->note          = 'Web Payment Deposit';
            $deposit->client_id     = (int)$request->clientId;
            $deposit->store_id      = (int)$request->storeId;
            $deposit->salesBy_id    = (int)$userId;
            $deposit->salesBy_name  = $userName;
            $deposit->subscriber_id = (int)$request->subscriberId;
            $deposit->order_id      = $request->orderId;
            $deposit->save();


        $totalPaid = 0;
        $checkXX = 0;

        $length = count($request->paymentDetails);
        // Log::info($length);

        if($length > 1){
            foreach($request->paymentDetails as $key=>$paymentDetail){
                $index = $key;
                // Log::info($index);
                if($index != 0){
                    // Log::info($index);
                    if($request->clientId == ""){
                        $clientId = 0;
                    }else{
                        $clientId = (int)$request->clientId;
                    }

                    if($checkXX == 0){
                        $payment = new Payment;
                        if($paymentDetail['payment_type'] == 'Cash'){

                            $payment->cash     = $paymentDetail['amount'];

                        }elseif($paymentDetail['payment_type'] == 'Card'){

                            $payment->card     = $paymentDetail['amount'];
                            $payment->bank_id     = $paymentDetail['id'];

                        }else{
                            $payment->mobile_bank     = $paymentDetail['amount'];
                            $payment->mobile_bank_type     = $paymentDetail['id'];

                        }

                        $payment->orderId       = $order->id;
                        $payment->clientId      = $clientId;
                        $payment->subscriber_id = (int)$request->subscriberId;

                        $payment->total = $request->grandTotal;
                        $payment->due = doubleval($request->due);
                        $payment->created_at = $deposit->created_at;

                        $payment->save();
                        $paymentId = $payment->id;
                    }else{
                        $payment = Payment::find($paymentId);
                        $due = $payment->due;
                        if($paymentDetail['payment_type'] == 'Cash'){
                            $payment->cash     = $paymentDetail['amount'];


                        }elseif($paymentDetail['payment_type'] == 'Card'){
                            $payment->card     = $paymentDetail['amount'];
                            $payment->bank_id     = $paymentDetail['id'];

                        }else{
                            $payment->mobile_bank     = $paymentDetail['amount'];
                            $payment->mobile_bank_type     = $paymentDetail['id'];

                        }

                        $payment->save();
                    }

                    // if($paymentDetail['amount'] != $request->due){
                    //     $deposit = new Deposit;
                    //     if($request->clientId == ""){
                    //         $lastBalance = 0;
                    //         $deposit->balance       = $lastBalance;
                    //     }else{
                    //         $clientId = (int)$request->clientId;
                    //         $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();
                    //         if($lastBalance == null){
                    //             $deposit->balance       = 0;
                    //         }else{
                    //             $deposit->balance       = $lastBalance->balance;
                    //         }
                    //     }

                    //     $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
                    //     $deposit->due           = 0;
                    //     $deposit->balance       = $deposit->balance;
                    //     $deposit->deposit       = $paymentDetail['amount'];
                    //     $deposit->status        = 'Web POS Payment';
                    //     $deposit->deposit_type  = $paymentDetail['payment_type'];
                    //     $deposit->note          = 'Web Payment Deposit';
                    //     $deposit->client_id     = (int)$request->clientId;
                    //     $deposit->store_id      = (int)$request->storeId;
                    //     $deposit->salesBy_id    = (int)$userId;
                    //     $deposit->salesBy_name  = $userName;
                    //     $deposit->subscriber_id = (int)$request->subscriberId;
                    //     $deposit->order_id = $request->orderId;
                    //     $deposit->save();
                    // }



                    $checkXX++;
                }


            }
        }else{
            foreach($request->paymentDetails as $key=>$paymentDetail){

                if($request->clientId == ""){
                    $clientId = 0;
                }else{
                    $clientId = (int)$request->clientId;
                }
                if($checkXX == 0){
                    $payment = new Payment;
                    if($paymentDetail['payment_type'] == 'Cash'){

                        $payment->cash     = $paymentDetail['amount'];

                    }elseif($paymentDetail['payment_type'] == 'Card'){

                        $payment->card     = $paymentDetail['amount'];
                        $payment->bank_id     = $paymentDetail['id'];

                    }else{
                        $payment->mobile_bank     = $paymentDetail['amount'];
                        $payment->mobile_bank_type     = $paymentDetail['id'];

                    }

                    $payment->orderId       = $order->id;
                    $payment->clientId      = $clientId;
                    $payment->subscriber_id = (int)$request->subscriberId;

                    $payment->total = $request->grandTotal;
                    $payment->due = doubleval($request->due);
                    $payment->created_at = $deposit->created_at;


                    $payment->save();
                    $paymentId = $payment->id;
                }else{
                    $payment = Payment::find($paymentId);
                    $due = $payment->due;

                    if ($paymentDetail['payment_type'] == 'Cash') {
                        $payment->cash     = $paymentDetail['amount'];

                    } elseif($paymentDetail['payment_type'] == 'Card'){
                        $payment->card     = $paymentDetail['amount'];
                        $payment->bank_id     = $paymentDetail['id'];

                    }else{
                        $payment->mobile_bank     = $paymentDetail['amount'];
                        $payment->mobile_bank_type     = $paymentDetail['id'];

                    }

                    $payment->save();
                }

                // if($paymentDetail['amount'] != $request->due){
                //     $deposit = new Deposit;
                //     if($request->clientId == ""){
                //         $lastBalance = 0;
                //         $deposit->balance       = $lastBalance;
                //     }else{
                //         $clientId = (int)$request->clientId;
                //         $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();
                //         if($lastBalance == null){
                //             $deposit->balance       = 0;
                //         }else{
                //             $deposit->balance       = $lastBalance->balance;
                //         }
                //     }

                //     $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
                //     $deposit->due           = 0;
                //     $deposit->balance       = $deposit->balance;
                //     $deposit->deposit       = $paymentDetail['amount'];
                //     $deposit->status        = 'Web POS Payment';
                //     $deposit->deposit_type  = $paymentDetail['payment_type'];
                //     $deposit->note          = 'Web Payment Deposit';
                //     $deposit->client_id     = (int)$request->clientId;
                //     $deposit->store_id      = (int)$request->storeId;
                //     $deposit->salesBy_id    = (int)$userId;
                //     $deposit->salesBy_name  = $userName;
                //     $deposit->subscriber_id = (int)$request->subscriberId;
                //     $deposit->order_id = $request->orderId;
                //     $deposit->save();
                // }


                $checkXX++;
            }
        }



        foreach($request->orderDetails as $orderDetail){

            $users = User::where('contact_number', $request->salesBy)
                    ->orWhere('email', $request->salesBy)
                    ->get();

            foreach($users as $user){
                $userId =  $user->id;
            }
            if($orderDetail['type']!='Serialize'){

                $orderedProduct = new OrderedProduct;
                $orderedProduct->productId          = (int)$orderDetail['productId'];
                $orderedProduct->variant_id          = (int)$orderDetail['variantId'];
                $orderedProduct->productName        = $orderDetail['productName'];
                $orderedProduct->productType        = $orderDetail['type'];
                $orderedProduct->quantity           = $orderDetail['quantity'];
                $orderedProduct->offerItemId        = (int)$orderDetail['offerItemId'];
                $orderedProduct->offerName          = $orderDetail['offerName'];
                $orderedProduct->offerQuantity      = $orderDetail['offerQuantity'];
                $orderedProduct->totalDiscount      = $orderDetail['totalDiscount'];
                $orderedProduct->specialDiscount    = $request->specialDiscount;
                $orderedProduct->totalPrice         = $orderDetail['totalPrice'];
                $orderedProduct->grandTotal         = $orderDetail['grandTotal'];
                $orderedProduct->totalTax           = $orderDetail['totalTax'];
                $orderedProduct->orderId            = $order->id;
                // $orderedProduct->subscriber_id      = Auth::user()->id)->get();
                $orderedProduct->subscriber_id      = (int)$request->subscriberId;

                $orderedProduct->salesBy_id         = (int)$userId;

                $discountAmount = doubleval($orderDetail['discount']);
                $discounts = Discount::where('discount', $discountAmount)->get();

                foreach($discounts as $discount){
                    $orderedProductDiscount = new OrderedProductDiscount;


                    $orderedProductDiscount->discountId         = $discount->id;
                    $orderedProductDiscount->type               = $discount->discountType;
                    $orderedProductDiscount->discountAmount     = $discount->discount;
                    $orderedProductDiscount->discountName       = $discount->discountName;
                    $orderedProductDiscount->orderId            = $order->id;
                    $orderedProductDiscount->productId          = (int)$orderDetail['productId'];

                    $orderedProductDiscount->save();
                }

                $taxAmount = doubleval($orderDetail['tax']);
                $taxes = Vat::where('taxAmount', $taxAmount)->get();

                foreach($taxes as $tax){
                    $orderedProductTax = new OrderedProductTax;

                    $orderedProductTax->taxId       = $tax->id;
                    $orderedProductTax->taxName     = $tax->taxName;
                    $orderedProductTax->taxAmount   = $tax->taxAmount;
                    $orderedProductTax->orderId     = $order->id;
                    $orderedProductTax->productId   = (int)$orderDetail['productId'];

                    $orderedProductTax->save();

                }

                $orderedProduct->save();
            }
            else{
                $OrderedProduct = OrderedProduct::where([
                    ['productId', (int)$orderDetail['productId']],
                    ['variant_id',(int)$orderDetail['variantId']],
                    ['productType','Serialize'],
                    ['orderId', $order->id],
                    ])
                ->get();
                if($OrderedProduct->isNotEmpty()){

                    foreach($OrderedProduct as $product){
                        $OrderedProductId =  $product->id;
                    }
                    $orderedProduct = OrderedProduct::find($OrderedProductId);
                    $quantity=$orderedProduct->quantity;
                    $totalDiscount=$orderedProduct->totalDiscount;
                    $specialDiscount=$orderedProduct->specialDiscount;
                    $totalPrice=$orderedProduct->totalPrice;
                    $grandTotal=$orderedProduct->grandTotal;
                    $totalTax=$orderedProduct->totalTax;

                    $orderedProduct->quantity           = $quantity+$orderDetail['quantity'];
                    $orderedProduct->totalDiscount      = $totalDiscount+$orderDetail['totalDiscount'];
                    // $orderedProduct->specialDiscount    = $specialDiscount+$request->specialDiscount;
                    $orderedProduct->totalPrice         = $totalPrice+$orderDetail['totalPrice'];
                    $orderedProduct->grandTotal         = $grandTotal+$orderDetail['grandTotal'];
                    $orderedProduct->totalTax           = $totalTax+$orderDetail['totalTax'];

                    $orderedProduct->save();

                }
                else{
                    $orderedProduct = new OrderedProduct;
                    $orderedProduct->productId          = (int)$orderDetail['productId'];
                    $orderedProduct->variant_id         = (int)$orderDetail['variantId'];
                    $orderedProduct->productName        = $orderDetail['productName'];
                    $orderedProduct->productType        = $orderDetail['type'];
                    $orderedProduct->quantity           = $orderDetail['quantity'];
                    $orderedProduct->offerItemId        = (int)$orderDetail['offerItemId'];
                    $orderedProduct->offerName          = $orderDetail['offerName'];
                    $orderedProduct->offerQuantity      = $orderDetail['offerQuantity'];
                    $orderedProduct->totalDiscount      = $orderDetail['totalDiscount'];
                    $orderedProduct->specialDiscount    = $request->specialDiscount;
                    $orderedProduct->totalPrice         = $orderDetail['totalPrice'];
                    $orderedProduct->grandTotal         = $orderDetail['grandTotal'];
                    $orderedProduct->totalTax           = $orderDetail['totalTax'];
                    $orderedProduct->orderId            = $order->id;
                    // $orderedProduct->subscriber_id      = Auth::user()->id)->get();
                    $orderedProduct->subscriber_id      = (int)$request->subscriberId;

                    $orderedProduct->salesBy_id         = (int)$userId;

                    $discountAmount = doubleval($orderDetail['discount']);
                    $discounts = Discount::where('discount', $discountAmount)->get();

                    foreach($discounts as $discount){
                        $orderedProductDiscount = new OrderedProductDiscount;


                        $orderedProductDiscount->discountId         = $discount->id;
                        $orderedProductDiscount->type               = $discount->discountType;
                        $orderedProductDiscount->discountAmount     = $discount->discount;
                        $orderedProductDiscount->discountName       = $discount->discountName;
                        $orderedProductDiscount->orderId            = $order->id;
                        $orderedProductDiscount->productId          = (int)$orderDetail['productId'];

                        $orderedProductDiscount->save();
                    }

                    $taxAmount = doubleval($orderDetail['tax']);
                    $taxes = Vat::where('taxAmount', $taxAmount)->get();

                    foreach($taxes as $tax){
                        $orderedProductTax = new OrderedProductTax;

                        $orderedProductTax->taxId       = $tax->id;
                        $orderedProductTax->taxName     = $tax->taxName;
                        $orderedProductTax->taxAmount   = $tax->taxAmount;
                        $orderedProductTax->orderId     = $order->id;
                        $orderedProductTax->productId   = (int)$orderDetail['productId'];

                        $orderedProductTax->save();

                    }

                    $orderedProduct->save();
                }

                if($request->storeId=='inventory'){
                    $storeId=0;
                }
                else{
                    $storeId=$request->storeId;
                }

                $productSerial = ProductSerial::where([
                    ['productId', (int)$orderDetail['productId']],
                    ['variantId', (int)$orderDetail['variantId']],
                    ['storeId', $storeId],
                    ['serialNumber', (int)$orderDetail['serialNumber']],
                ])->get();

                if($productSerial->isNotEmpty()){
                    foreach($productSerial as $serial){
                        $serialId =  $serial->id;
                    }
                    $productSerial = ProductSerial::find($serialId);
                    $productSerial->saleId=$order->id;
                    $productSerial->saleDate= date('Y-m-d', $orderDateStr);
                    $productSerial->Save();
                }
            }
        }

        //---------------------------------------------STORE INVENTORY----------------------------------------------------------

        if($request->storeId=='inventory')
        {
            foreach($request->orderDetails as $orderDetail){
                $inventory = Inventory::where([
                                        ['productId', '=', (int)$orderDetail['productId'] ],
                                        ['variant_id', '=', (int)$orderDetail['variantId'] ]
                                    ])
                                    ->orderBy('created_at', 'asc')
                                    ->get();

                $orderId = $request->orderId;

                $offerQuantity = (int)$orderDetail['offerQuantity'];

                //Offer Item Quantity Adjustment Code Here
                if($offerQuantity > 0){
                    $variantId = (int)$orderDetail['offerItemId'];
                    $variant = Variant::where('id', $variantId)->first();
                    $offerProductId = $variant->product_id;

                    $offer_inventory = Inventory::where([
                            ['productId', '=', $offerProductId ],
                            ['variant_id', '=', $variantId]
                        ])
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach($offer_inventory as $offer_inventory){
                        if($offer_inventory->onHand > $offerQuantity){
                            $inventoryOffer = Inventory::find($offer_inventory->id);
                            $inventoryOffer->onHand = $inventoryOffer->onHand - $offerQuantity;
                            $inventoryOffer->productOutgoing = $inventoryOffer->productOutgoing + $offerQuantity;
                            $inventoryOffer->save();
                            break;
                        }
                    }

                }

                //Main Product Quantity Adjustment Code
                foreach($inventory as $storeInventory){
                    $variantId = $storeInventory->variant_id;

                    $id = $storeInventory->id;
                    $inventory = Inventory::find($id);

                    $orderQty = $orderDetail['quantity'];
                    $stockQty = $inventory->onHand;
                    $productOutgoing = $inventory->productOutgoing;


                    $inventory->onHand = $stockQty - $orderQty;
                    $inventory->productOutgoing = $productOutgoing + $orderQty;
                    $inventory->save();

                }
            }
        }
        else
        {    foreach($request->orderDetails as $orderDetail){
                $store_inventory = StoreInventory::where([
                                        ['store_id', '=', $request->storeId],
                                        ['productId', '=', (int)$orderDetail['productId'] ],
                                        ['variant_id', '=', (int)$orderDetail['variantId'] ]
                                    ])
                                    ->orderBy('created_at', 'asc')
                                    ->get();

                $orderId = $request->orderId;

                $offerQuantity = (int)$orderDetail['offerQuantity'];

                //Offer Item Quantity Adjustment Code Here
                if($offerQuantity > 0){
                    $variantId = (int)$orderDetail['offerItemId'];
                    $variant = Variant::where('id', $variantId)->first();
                    $offerProductId = $variant->product_id;

                    $offer_store_inventory = StoreInventory::where([
                            ['store_id', '=', $request->storeId],
                            ['productId', '=', $offerProductId ],
                            ['variant_id', '=', $variantId]
                        ])
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach($offer_store_inventory as $offer_store){
                        if($offer_store->onHand > $offerQuantity){
                            $storeOffer = StoreInventory::find($offer_store->id);
                            $storeOffer->onHand = $storeOffer->onHand - $offerQuantity;
                            $storeOffer->productOutgoing = $storeOffer->productOutgoing + $offerQuantity;
                            $storeOffer->save();
                            break;
                        }
                    }

                }

                //Main Product Quantity Adjustment Code
                foreach($store_inventory as $storeInventory){
                    $variantId = $storeInventory->variant_id;

                    $id = $storeInventory->id;
                    $store = StoreInventory::find($id);

                    $orderQty = $orderDetail['quantity'];
                    $stockQty = $store->onHand;
                    $productOutgoing = $store->productOutgoing;


                    $store->onHand = $stockQty - $orderQty;
                    $store->productOutgoing = $productOutgoing + $orderQty;
                    $store->save();

                }
            }
        }

        return response() -> json([
            'status'=>200,
            'orderId'=>$request->orderId,
            'message' => 'Order created successfully!'
        ]);

    }

    public function allorederstore(Request $request)
    {
        // Log::info($request);
        $order = new Order;

        $users = User::where('contact_number', $request->salesBy)
            ->orWhere('email', $request->salesBy)
            ->get();

        foreach ($users as $user) {
            $userId =  $user->id;
            $userName = $user->name;
        }

        $order->orderId         = $request->orderId;
        $order->clientId        = (int)$request->clientId;
        $order->total           = $request->total;
        $order->specialDiscount = $request->specialDiscount;
        $order->totalDiscount   = $request->totalDiscount;
        $order->grandTotal      = $request->grandTotal;
        $order->subscriber_id   = (int)$request->subscriberId;
        $order->store_id        = (int)$request->storeId;
        // $order->pos_id          = (int)$request->posId;

        $order->salesBy_id      = (int)$userId;

        $orderDateStr           = strtotime($request->orderDate);
        $order->orderDate       = date('Y-m-d', $orderDateStr);

        // $order->paymentType     = $request->paymentType;
        $order->totalTax        = $request->totalTax;


        if ($request->referenceId != null || $request->referenceId != "") {
            $checkReferences = HoldSale::where('reference', $request->referenceId)->get();
            foreach ($checkReferences as $checkReference) {
                $findReference = HoldSale::find($checkReference->id)->delete($checkReference->id);
            }
        }


        $order->save();

        $lastBalance = DB::table('deposits')->where('client_id', (int)$request->clientId)->latest()->first();
        $deposit = new Deposit;

        $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
        $deposit->due           = (int)$request->due;
        $deposit->balance       = $lastBalance->balance - (int)$request->due;
        $deposit->deposit       = (int)$request->deposit;
        $deposit->status        = 'Web POS Payment';
        $deposit->deposit_type  = 'Payment';
        $deposit->note          = 'Web Payment Deposit';
        $deposit->client_id     = (int)$request->clientId;
        $deposit->store_id      = (int)$request->storeId;
        $deposit->salesBy_id    = (int)$userId;
        $deposit->salesBy_name  = $userName;
        $deposit->subscriber_id = (int)$request->subscriberId;
        $deposit->order_id = $request->orderId;
        $deposit->save();


        $totalPaid = 0;
        $checkXX = 0;

        $length = count($request->paymentDetails);
        // Log::info($length);

        if ($length > 1) {
            foreach ($request->paymentDetails as $key => $paymentDetail) {
                $index = $key;
                // Log::info($index);
                if ($index != 0) {
                    // Log::info($index);
                    if ($request->clientId == "") {
                        $clientId = 0;
                    } else {
                        $clientId = (int)$request->clientId;
                    }

                    if ($checkXX == 0) {
                        $payment = new Payment;
                        if(doubleval($request->due)!= $request->grandTotal)
                        {
                        if ($paymentDetail['payment_type'] == 'cash') {
                            $payment->cash     = $paymentDetail['amount'];
                        } elseif ($paymentDetail['payment_type'] == 'bank') {

                            $payment->card     = $paymentDetail['amount'];
                            $payment->bank_id     = $paymentDetail['id'];
                        } else {
                            $payment->mobile_bank     = $paymentDetail['amount'];
                            $payment->mobile_bank_type     = $paymentDetail['id'];
                        }
                        }
                        $payment->orderId       = $order->id;
                        $payment->clientId      = $clientId;
                        $payment->subscriber_id = (int)$request->subscriberId;

                        $payment->total = $request->grandTotal;
                        $payment->due = doubleval($request->due);
                        $payment->created_at = $deposit->created_at;


                        $payment->save();
                        $paymentId = $payment->id;
                    } else {
                        $payment = Payment::find($paymentId);
                        $due = $payment->due;
                        if ($paymentDetail['payment_type'] == 'cash') {
                            $payment->cash     = $paymentDetail['amount'];
                        } elseif ($paymentDetail['payment_type'] == 'bank') {
                            $payment->card     = $paymentDetail['amount'];
                            $payment->bank_id     = $paymentDetail['id'];
                        } else {
                            $payment->mobile_bank     = $paymentDetail['amount'];
                            $payment->mobile_bank_type     = $paymentDetail['id'];
                        }

                        $payment->save();
                    }

                    // if ($paymentDetail['amount'] != $request->due) {
                    //     $deposit = new Deposit;
                    //     if ($request->clientId == "") {
                    //         $lastBalance = 0;
                    //         $deposit->balance       = $lastBalance;
                    //     } else {
                    //         $clientId = (int)$request->clientId;
                    //         $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();
                    //         if ($lastBalance == null) {
                    //             $deposit->balance       = 0;
                    //         } else {
                    //             $deposit->balance       = $lastBalance->balance;
                    //         }
                    //     }

                    //     $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
                    //     $deposit->due           = 0;
                    //     $deposit->balance       = $deposit->balance + $paymentDetail['amount'];
                    //     $deposit->deposit       = $paymentDetail['amount'];
                    //     $deposit->status        = 'Web POS Payment';
                    //     $deposit->deposit_type  = $paymentDetail['payment_type'];
                    //     $deposit->note          = 'Web Payment Deposit';
                    //     $deposit->client_id     = (int)$request->clientId;
                    //     $deposit->store_id      = (int)$request->storeId;
                    //     $deposit->salesBy_id    = (int)$userId;
                    //     $deposit->salesBy_name  = $userName;
                    //     $deposit->subscriber_id = (int)$request->subscriberId;
                    //     $deposit->order_id = $request->orderId;
                    //     $deposit->save();
                    // }



                    $checkXX++;
                }
            }
        } else {
            foreach ($request->paymentDetails as $key => $paymentDetail) {

                if ($request->clientId == "") {
                    $clientId = 0;
                } else {
                    $clientId = (int)$request->clientId;
                }
                if ($checkXX == 0) {
                    $payment = new Payment;
                    if (doubleval($request->due) != $request->grandTotal) {
                    if ($paymentDetail['payment_type'] == 'cash') {

                        $payment->cash     = $paymentDetail['amount'];
                    } elseif ($paymentDetail['payment_type'] == 'bank') {

                        $payment->card     = $paymentDetail['amount'];
                        $payment->bank_id     = $paymentDetail['id'];
                    } else {
                        $payment->mobile_bank     = $paymentDetail['amount'];
                        $payment->mobile_bank_type     = $paymentDetail['id'];
                    }
                }
                    $payment->orderId       = $order->id;
                    $payment->clientId      = $clientId;
                    $payment->subscriber_id = (int)$request->subscriberId;

                    $payment->total = $request->grandTotal;
                    $payment->due = doubleval($request->due);
                    $payment->created_at = $deposit->created_at;

                    $payment->save();
                    $paymentId = $payment->id;
                } else {
                    $payment = Payment::find($paymentId);
                    $due = $payment->due;

                    if ($paymentDetail['payment_type'] == 'cash') {
                        $payment->cash     = $paymentDetail['amount'];
                    } elseif ($paymentDetail['payment_type'] == 'bank') {
                        $payment->card     = $paymentDetail['amount'];
                        $payment->bank_id     = $paymentDetail['id'];
                    } else {
                        $payment->mobile_bank     = $paymentDetail['amount'];
                        $payment->mobile_bank_type     = $paymentDetail['id'];
                    }

                    $payment->save();
                }

                // if ($paymentDetail['amount'] != $request->due) {
                //     $deposit = new Deposit;
                //     if ($request->clientId == "") {
                //         $lastBalance = 0;
                //         $deposit->balance       = $lastBalance;
                //     } else {
                //         $clientId = (int)$request->clientId;
                //         $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();
                //         if ($lastBalance == null) {
                //             $deposit->balance       = 0;
                //         } else {
                //             $deposit->balance       = $lastBalance->balance;
                //         }
                //     }

                //     $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
                //     $deposit->due           = 0;
                //     $deposit->balance       = $deposit->balance + $paymentDetail['amount'];
                //     $deposit->deposit       = $paymentDetail['amount'];
                //     $deposit->status        = 'Web POS Payment';
                //     $deposit->deposit_type  = $paymentDetail['payment_type'];
                //     $deposit->note          = 'Web Payment Deposit';
                //     $deposit->client_id     = (int)$request->clientId;
                //     $deposit->store_id      = (int)$request->storeId;
                //     $deposit->salesBy_id    = (int)$userId;
                //     $deposit->salesBy_name  = $userName;
                //     $deposit->subscriber_id = (int)$request->subscriberId;
                //     $deposit->order_id = $request->orderId;
                //     $deposit->save();
                // }


                $checkXX++;
            }
        }



        foreach ($request->orderDetails as $orderDetail) {

            $orderedProduct = new OrderedProduct;

            $users = User::where('contact_number', $request->salesBy)
                ->orWhere('email', $request->salesBy)
                ->get();

            foreach ($users as $user) {
                $userId =  $user->id;
            }

            $orderedProduct->productId          = (int)$orderDetail['productId'];
            $orderedProduct->variant_id          = (int)$orderDetail['variantId'];
            $orderedProduct->productName        = $orderDetail['productName'];
            $orderedProduct->quantity           = $orderDetail['quantity'];
            $orderedProduct->offerItemId        = (int)$orderDetail['offerItemId'];
            $orderedProduct->offerName          = $orderDetail['offerName'];
            $orderedProduct->offerQuantity      = $orderDetail['offerQuantity'];
            $orderedProduct->totalDiscount      = $orderDetail['totalDiscount'];
            $orderedProduct->specialDiscount    = $orderDetail['specialDiscount'];
            $orderedProduct->totalPrice         = $orderDetail['totalPrice'];
            $orderedProduct->grandTotal         = $orderDetail['grandTotal'];
            $orderedProduct->totalTax           = $orderDetail['totalTax'];
            $orderedProduct->orderId            = $order->id;
            // $orderedProduct->subscriber_id      = Auth::user()->id)->get();
            $orderedProduct->subscriber_id      = (int)$request->subscriberId;

            $orderedProduct->salesBy_id         = (int)$userId;

            $discountAmount = doubleval($orderDetail['discount']);
            $discounts = Discount::where('discount', $discountAmount)->get();

            foreach ($discounts as $discount) {
                $orderedProductDiscount = new OrderedProductDiscount;


                $orderedProductDiscount->discountId         = $discount->id;
                $orderedProductDiscount->type               = $discount->discountType;
                $orderedProductDiscount->discountAmount     = $discount->discount;
                $orderedProductDiscount->discountName       = $discount->discountName;
                $orderedProductDiscount->orderId            = $order->id;
                $orderedProductDiscount->productId          = (int)$orderDetail['productId'];

                $orderedProductDiscount->save();
            }

            $taxAmount = doubleval($orderDetail['tax']);
            $taxes = Vat::where('taxAmount', $taxAmount)->get();

            foreach ($taxes as $tax) {
                $orderedProductTax = new OrderedProductTax;

                $orderedProductTax->taxId       = $tax->id;
                $orderedProductTax->taxName     = $tax->taxName;
                $orderedProductTax->taxAmount   = $tax->taxAmount;
                $orderedProductTax->orderId     = $order->id;
                $orderedProductTax->productId   = (int)$orderDetail['productId'];

                $orderedProductTax->save();
            }

            $orderedProduct->save();
        }

        //---------------------------------------------STORE INVENTORY----------------------------------------------------------

        foreach ($request->orderDetails as $orderDetail) {
            $store_inventory = StoreInventory::where([
                ['store_id', '=', $request->storeId],
                ['productId', '=', (int)$orderDetail['productId']],
                ['variant_id', '=', (int)$orderDetail['variantId']]
            ])
                ->orderBy('created_at', 'asc')
                ->get();

            $orderId = $request->orderId;

            $offerQuantity = (int)$orderDetail['offerQuantity'];

            //Offer Item Quantity Adjustment Code Here
            if ($offerQuantity > 0) {
                $variantId = (int)$orderDetail['offerItemId'];
                $variant = Variant::where('id', $variantId)->first();
                $offerProductId = $variant->product_id;

                $offer_store_inventory = StoreInventory::where([
                    ['store_id', '=', $request->storeId],
                    ['productId', '=', $offerProductId],
                    ['variant_id', '=', $variantId]
                ])
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($offer_store_inventory as $offer_store) {
                    if ($offer_store->onHand > $offerQuantity) {
                        $storeOffer = StoreInventory::find($offer_store->id);
                        $storeOffer->onHand = $storeOffer->onHand - $offerQuantity;
                        $storeOffer->productOutgoing = $storeOffer->productOutgoing + $offerQuantity;
                        $storeOffer->save();
                        break;
                    }
                }
            }

            //Main Product Quantity Adjustment Code
            foreach ($store_inventory as $storeInventory) {
                $variantId = $storeInventory->variant_id;

                $id = $storeInventory->id;
                $store = StoreInventory::find($id);

                $orderQty = $orderDetail['quantity'];
                $stockQty = $store->onHand;
                $productOutgoing = $store->productOutgoing;


                $store->onHand = $stockQty - $orderQty;
                $store->productOutgoing = $productOutgoing + $orderQty;
                $store->save();
            }
        }

        return response()->json([
            'status' => 200,
            'orderId' => $request->orderId,
            'message' => 'Order created successfully!'
        ]);
    }

    public function listView(Request $request){
        // return view('order/order-list');
        $salesBy = User::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('order/order-list', ['salesBy' => $salesBy]);
    }

    public function showReport(Request $request, $startdate, $enddate)
    {
        // Log::info($request);
        //Log::info($startdate);
        $from = $startdate;
        $to = $enddate;

        $columns = array(
            0 => 'orderId',
            1 => 'name',
            2 => 'mobile',
            3 => 'total',
            4 => 'specialDiscount ',
            5 => 'totalDiscount',
            6 => 'grandTotal',
            7 => 'orderDate',
            8 => 'totalTax',
            9 => 'id',
            9=> 'salesReturn'
        );



        $totalData = Order::where('subscriber_id', Auth::user()->subscriber_id)
        ->whereBetween('orders.orderDate', ["$from", "$to"])
        ->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns['1'];
        $dir = $request->input('order.0.dir');


        if (empty($request->input('search.value'))) {
            $orders = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
            ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->whereBetween('orders.orderDate', ["$from", "$to"])
                ->offset($start)
                ->limit($limit)
                ->orderBy('orders.id', 'desc')
                ->get(['orders.*', 'clients.name', 'clients.mobile']);
        } else {
            $search = $request->input('search.value');

            $orders =  Order::leftjoin('clients', 'clients.id', 'orders.clientId')
            ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->where('orders.orderId', 'LIKE', "%{$search}%")
                ->whereBetween('orders.orderDate', ["$from", "$to"])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get(['orders.*', 'clients.name', 'clients.mobile']);

            $totalFiltered = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
            ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->where('orders.orderId', 'LIKE', "%{$search}%")
                ->whereBetween('orders.orderDate', ["$from", "$to"])
                ->count();
        }
        foreach ($orders as $order){
            $salesReturn = SalesReturn::where('invoice_no', $order->orderId)->first();
            if($salesReturn!=null){
                $order['salesReturn']= $salesReturn;
            }
            else{
                $order['salesReturn']= 0.00;
            }

        }
        $data = array();

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $nestedData['orderId'] = $order->orderId;
                $nestedData['name'] = $order->name;
                $nestedData['mobile'] = $order->mobile;
                $nestedData['total'] = $order->total;
                $nestedData['specialDiscount'] = $order->specialDiscount;
                $nestedData['totalDiscount'] = $order->totalDiscount;
                $nestedData['grandTotal'] = $order->grandTotal;
                $nestedData['orderDate'] = $order->orderDate;
                $nestedData['totalTax'] = $order->totalTax;
                $nestedData['id'] = $order->id;
                $nestedData['salesReturn'] = $order->salesReturn;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        log::info($json_data);
        return json_encode($json_data);
    }

    public function list(Request $request){
        $columns = array(
                    0 =>'orderId',
                    1 =>'name',
                    2=> 'mobile',
                    3=> 'total',
                    4=> 'totalDiscount',
                    5=> 'grandTotal',
                    6=> 'orderDate',
                    7=> 'totalTax',
                    8=> 'id',
                    9=> 'salesReturn'
                );

        $totalData = Order::where('subscriber_id', Auth::user()->subscriber_id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $orders = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
                ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                ->offset($start)
                ->limit($limit)
                ->orderBy('orders.id', 'desc')
                ->get(['orders.*', 'clients.name', 'clients.mobile']);

        }else{
            $search = $request->input('search.value');

            $orders =  Order::leftjoin('clients', 'clients.id', 'orders.clientId')
                    ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                    ->where('orders.orderId','LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get(['orders.*', 'clients.name', 'clients.mobile']);

            $totalFiltered = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
                            ->where('orders.subscriber_id', Auth::user()->subscriber_id)
                            ->where('orders.orderId','LIKE',"%{$search}%")
                            ->count();
        }

        foreach ($orders as $order){
            $salesReturn = SalesReturn::where('invoice_no', $order->orderId)->first();
            if($salesReturn!=null){
                $order['salesReturn']= $salesReturn;
            }
            else{
                $order['salesReturn']= 0.00;
            }

        }
        $data = array();

        if(!empty($orders))
        {
            foreach ($orders as $order)
            {
                $nestedData['orderId'] = $order->orderId;
                $nestedData['name'] = $order->name;
                $nestedData['mobile'] = $order->mobile;
                $nestedData['total'] = $order->total;
                $nestedData['totalDiscount'] = $order->totalDiscount;
                $nestedData['specialDiscount'] = $order->specialDiscount;
                $nestedData['grandTotal'] = $order->grandTotal;
                $nestedData['orderDate'] = $order->orderDate;
                $nestedData['totalTax'] = $order->totalTax;
                $nestedData['id'] = $order->id;
                $nestedData['salesReturn'] = $order->salesReturn;

                $data[] = $nestedData;
            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                );

        return json_encode( $json_data);


    }

    public function productListView($id){

        $productList = OrderedProduct::where([
                ['orderId', $id],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->get();

        // $orderList = Order::where([
        //         ['id', $id],
        //         ['subscriber_id', Auth::user()->subscriber_id]
        //     ])->get();

        $orderList = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
                    ->where([
                        ['orders.id', $id],
                        ['orders.subscriber_id', Auth::user()->subscriber_id]
                    ])->get(['orders.*', 'clients.name', 'clients.mobile']);

        return view('order/orderproduct-list', ['productList' => $productList, 'orderList' => $orderList]);
    }

    public function productList(Request $request, $id){

        $productList = OrderedProduct::where([
                ['orderId', $id],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->get();

        // $orderList = Order::where([
        //         ['id', $id],
        //         ['subscriber_id', Auth::user()->subscriber_id]
        //     ])->get();

        // $orderList = Order::join('clients', 'clients.id', 'orders.clientId')
        // ->where([
        //     ['orders.id', $id],
        //     ['orders.subscriber_id', Auth::user()->subscriber_id]
        // ])->get(['orders.*', 'clients.name', 'clients.mobile']);

        if($request -> ajax()){
            return response()->json([
                'productList'=>$productList,
            ]);
        }
    }

    public function productSerialList($orderId, $productId, $variantId){

        $serialList = ProductSerial::where([
                ['saleId', $orderId],
                ['productId', $productId],
                ['variantId', $variantId],
            ])->get();

        // $orderList = Order::where([
        //         ['id', $id],
        //         ['subscriber_id', Auth::user()->subscriber_id]
        //     ])->get();

        $orderList = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
                    ->rightjoin('ordered_products', 'ordered_products.orderId', 'orders.id')
                    ->where([
                        ['orders.id', $orderId],
                        ['orders.subscriber_id', Auth::user()->subscriber_id],
                        ['ordered_products.productType', 'Serialize']
                    ])->get(['orders.orderDate', 'ordered_products.*','clients.name', 'clients.mobile']);

        return view('order/orderproductserial-list', ['serialList' => $serialList, 'orderList' => $orderList]);
    }

    public function orderDetailsForAppView($id){

        // $orderList = Order::where([
        //         ['id', $id],
        //         ['subscriber_id', Auth::user()->subscriber_id]
        //     ])->get();

        $orderList = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
                    ->where([
                        ['orders.orderId', $id],
                        // ['orders.subscriber_id', Auth::user()->subscriber_id]
                    ])->get(['orders.*', 'clients.name', 'clients.mobile']);

        return view('order/order-details-for-app', ['orderList' => $orderList]);

    }

    public function productListForApp(Request $request, $id){

        $productList = OrderedProduct::where([
                ['orderId', $id]
            ])->get();

        // $orderList = Order::where([
        //         ['id', $id],
        //         ['subscriber_id', Auth::user()->subscriber_id]
        //     ])->get();

        // $orderList = Order::join('clients', 'clients.id', 'orders.clientId')
        // ->where([
        //     ['orders.id', $id],
        //     ['orders.subscriber_id', Auth::user()->subscriber_id]
        // ])->get(['orders.*', 'clients.name', 'clients.mobile']);

        if($request -> ajax()){
            return response()->json([
                'productList'=>$productList,
            ]);
        }
    }

    public function checkofferQty(Request $request){
        foreach($request->orderDetails as $orderDetail){

            $offerQuantity = doubleval($orderDetail['offerQuantity']);
            $variantId = (int)$orderDetail['offerItemId'];

            if($variantId == NULL){
                return response() -> json([
                    'status'=>200,
                    'check' => 'True'
                ]);
            }else{
                $variant = Variant::where('id', $variantId)->first();

                $productIdOfferItem = $variant->product_id;

                $status = 1;

                if($offerQuantity > 0){
                    $offer_store_inventory = StoreInventory::where([
                            ['store_id', '=', $request->storeId],
                            ['productId', '=', $productIdOfferItem ],
                            ['variant_id', '=', $variantId]
                        ])
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach($offer_store_inventory as $offer_store){
                        if($offer_store->onHand > $offerQuantity){
                            $status = 1;
                        }else{
                            $status = 0;
                            break;
                        }
                    }
                }

                if($status == 0){
                    break;
                }
            }

            if($status == 1){
                return response() -> json([
                    'status'=>200,
                    'check' => 'True'
                ]);
            }else{
                return response() -> json([
                    'status'=>200,
                    'check' => 'False'
                ]);
            }
        }


    }
}
