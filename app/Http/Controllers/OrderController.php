<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Session;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\DuePayment;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\OrderedProduct;
use App\Models\StoreInventory;
use App\Models\OrderedProductTax;
use App\Models\OrderedProductDiscount;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        foreach ($request->orderDetails as $orderDetail) {

            if($orderDetail['type']=='Serialize'){
                
                if(count($orderDetail['serialNumbers'])==(int)$orderDetail['quantity']){
                    foreach ($orderDetail['serialNumbers'] as $serialNumber) {

                        $productSerial = ProductSerial::where([
                            ['productId', (int)$orderDetail['productId']],
                            ['variantId', (int)$orderDetail['variantId']],
                            ['storeId', $request->storeId],
                            ['serialNumber', (int)$serialNumber['serialNumber']],
                            ['saleId', 0]
                        ])->get();

                        if($productSerial->isEmpty()){
                            return response() -> json([
                                'status'=>200,
                                'serialNumber'=>(int)$serialNumber['serialNumber'],
                                'message' => 'Serial Number Invalid'
                            ]);
                        }
                    }
                }
                else{
                    return response() -> json([
                        'status'=>200,
                        'message' => 'Every serialize product must have sreial number. '
                    ]);
                }
            }
        }

        Session::put('subscriberId', $request->subscriberId);
        $order = new Order;

        $users = User::where('contact_number', $request->salesBy)
            ->orWhere('email', $request->salesBy)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => 'No User'
            ]);
        } else {
            foreach ($users as $user) {
                $userId =  $user->id;
                $userName = $user->name;
            }
        }


        $order->orderId         = $request->orderId;
        $order->clientId        = (int)$request->clientId;
        $order->total           = $request->total;
        $order->totalDiscount   = $request->totalDiscount;
        $order->specialDiscount = $request->specialDiscount;
        $order->grandTotal      = $request->grandTotal;
        $order->subscriber_id   = (int)$request->subscriberId;
        $order->store_id        = (int)$request->storeId;
        $order->pos_id          = (int)$request->posId;

        $order->salesBy_id      = (int)$userId;


        $orderDateStr           = strtotime($request->orderDate);
        $order->orderDate       = date('Y-m-d', $orderDateStr);

        // $order->paymentType     = $request->paymentType;
        $order->totalTax        = $request->totalTax;
        $order->created_by      = (int)$request->subscriberId;

        $order->save();

        if ($request->clientId == "") {
            $clientId = 0;
        } else {
            $clientId = (int)$request->clientId;
        }

            $deposit = new Deposit;
            $depositDate                = strtotime($request->orderDate);
            $deposit->deposit_date      = date('Y-m-d', $depositDate);

            $paidAmount = doubleval($request->grandTotal) - doubleval($request->due);

            $deposit->due               = $request->due;
            $deposit->deposit           = $paidAmount;
            $deposit->deposit_type      = 'Manual';
            $lastBalance = DB::table('deposits')->where('client_id', $clientId)->orderBy('id', 'desc')->limit(1)->first();

            if ($lastBalance === null) {
                $lastBalance = 0;
                $deposit->balance           = $lastBalance + $paidAmount - $request->grandTotal;
            } else {
                $deposit->balance           = $lastBalance->balance + $paidAmount - $request->grandTotal;
            }
            $deposit->status            = 'App deposit';
            $deposit->order_id          = $order->orderId;

        $deposit->client_id         = $clientId;
        $deposit->store_id          = (int)$request->storeId;
        $deposit->salesBy_id        = (int)$request->userId;
        $deposit->salesBy_name      = $userName;
        $deposit->subscriber_id     = (int)$request->subscriberId;

        $deposit->save();

        $totalPaid = 0;
        $checkXX = 0;
        foreach ($request->paymentDetails as $paymentDetail) {




            // $payment = new Payment;
            // if($checkXX == 0){

            //     if($paymentDetail['type'] == 'cash'){

            //         $payment->cash     = $paymentDetail['amount'];

            $payment = new Payment;
            if ($checkXX == 0) {
                //$payment = new Payment;
                if ($paymentDetail['type'] == 'cash') {

                    $payment->cash     = $paymentDetail['amount'];

                    // Log::info("hi");


                    $cash     = $paymentDetail['amount'];
                } elseif ($paymentDetail['type'] == 'card') {

                    $payment->card     = $paymentDetail['amount'];
                    $paycard     = $paymentDetail['amount'];
                }
                // elseif($paymentDetail['type'] == 'mobile_bank') {
                else{
                    $payment->mobile_bank     = $paymentDetail['amount'];
                    $payment->mobile_bank_type     = $paymentDetail['id'];
                }
                // else{
                //     $payment->mobile_bank     = 0;
                //     $payment->cash     = 0;
                //     $payment->card=0;
                // }


                //$payment->due = $request->total-($payment->cash+$payment->card);


                //$payment->due = $request->total-($payment->cash+$payment->card);
                // $payment->due = $request->total - ($payment->cash + $payment->card + $payment->mobile_bank);
                $payment->orderId       = $order->id;
                $payment->clientId      = $clientId;
                $payment->subscriber_id = (int)$request->subscriberId;
                $payment->due = doubleval($request->due);

                $payment->total = $request->grandTotal;

                // Log::info($request->total-($payment->cash+$payment->card+$payment->mobile_bank));
                // $payment->due = doubleval($request->due);


                $payment->save();
                $paymentId = $payment->id;
            } else {

                $payment = Payment::find($paymentId);
                $due = $payment->due;
                if ($paymentDetail['type'] == 'cash') {
                    $payment->cash     = $paymentDetail['amount'];
                } elseif ($paymentDetail['type'] == 'card') {
                    $payment->card     = $paymentDetail['amount'];
                } else {
                    $payment->mobile_bank     = $paymentDetail['amount'];
                    $payment->mobile_bank_type     = $paymentDetail['id'];
                }
                //    Log::info("message");
                $payment->save();
            }

            // $payment = Payment::find($paymentId);
            // $cash = $payment->cash;
            // $card = $payment->card;
            // $mobileBank = $payment->mobile_bank;
            // $totalPaid = $cash + $card + $mobileBank;
            // $total = $payment->total;
            // $due = $total - $totalPaid;
            // $payment->due =  $due;
            // $payment->save();


            // $deposit = new Deposit;
            // if ($request->clientId == "") {
            //     $lastBalance = 0;
            //     $deposit->balance       = $lastBalance;
            // } else {
            //     $clientId = (int)$request->clientId;
            //     $lastBalance = DB::table('deposits')->where('client_id', $clientId)->latest()->first();
            //     if ($lastBalance == null) {
            //         $deposit->balance       = 0;
            //     } else {
            //         $deposit->balance       = $lastBalance->balance;
            //     }
            // }

            // $deposit->deposit_date  = date('Y-m-d', $orderDateStr);
            // $deposit->due           = 0;
            // $deposit->deposit       = $paymentDetail['amount'];
            // $deposit->status        = 'App Sale Payment';
            // $deposit->deposit_type  = $paymentDetail['type'];
            // $deposit->note          = 'App Payment Deposit';
            // $deposit->client_id     = (int)$request->clientId;
            // $deposit->store_id      = (int)$request->storeId;
            // $deposit->salesBy_id    = (int)$userId;
            // $deposit->salesBy_name  = $userName;
            // $deposit->subscriber_id = (int)$request->subscriberId;
            // $deposit->save();

            $checkXX++;
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
            $orderedProduct->specialDiscount    = $request->specialDiscount;
            $orderedProduct->totalDiscount      = $orderDetail['totalDiscount'];
            $orderedProduct->totalPrice         = $orderDetail['totalPrice'];
            $orderedProduct->grandTotal         = $orderDetail['grandTotal'];
            $orderedProduct->totalTax           = $orderDetail['totalTax'];
            $orderedProduct->orderId            = $order->id;
            // $orderedProduct->subscriber_id      = Auth::user()->id)->get();
            $orderedProduct->subscriber_id      = (int)$request->subscriberId;

            $orderedProduct->salesBy_id         = (int)$userId;



            foreach ($orderDetail['discountDetails'] as $discountDetail) {

                // foreach($request->orderDetails['discountDetails'] as $discountDetail){

                $orderedProductDiscount = new OrderedProductDiscount;

                // $orderedProductDiscount->discountId         = (int)$discountDetail['discountId'];
                $orderedProductDiscount->discountId         = (int)$discountDetail['id'];

                // $orderedProductDiscount->type               = $discountDetail['discountType'];
                $orderedProductDiscount->type               = $discountDetail['discountType'];

                $orderedProductDiscount->discountAmount     = $discountDetail['discount'];
                // $orderedProductDiscount->discountName       = $discountDetail['discountName'];
                $orderedProductDiscount->discountName       = $discountDetail['discountName'];

                $orderedProductDiscount->orderId            = $order->id;
                $orderedProductDiscount->productId          = (int)$orderDetail['productId'];

                $orderedProductDiscount->created_by = (int)$request->subscriberId;
                $orderedProductDiscount->save();
            }

            foreach ($orderDetail['tax'] as $tax) {
                $orderedProductTax = new OrderedProductTax;

                $orderedProductTax->taxId       = (int)$tax['id'];
                $orderedProductTax->taxName     = $tax['taxName'];
                $orderedProductTax->taxAmount   = $tax['taxAmount'];
                $orderedProductTax->orderId     = $order->id;
                $orderedProductTax->productId   = (int)$orderDetail['productId'];

                $orderedProductTax->created_by = (int)$request->subscriberId;

                $orderedProductTax->save();
            }

            $orderedProduct->created_by = (int)$request->subscriberId;
            $orderedProduct->save();

            $store_inventory = StoreInventory::where([
                ['store_id', '=', $request->storeId],
                ['productId', '=', (int)$orderDetail['productId']],
                ['variant_id', '=', (int)$orderDetail['variantId']]
            ])
                ->orderBy('created_at', 'asc')
                ->get();

            $orderId = $request->orderId;

            $offerQuantity = (int)$orderDetail['offerQuantity'];
            $offerItemId = (int)$orderDetail['offerItemId'];

            if ($offerQuantity > 0) {
                $offer_store_inventory = StoreInventory::where([
                    ['store_id', '=', $request->storeId],
                    ['productId', '=', $offerItemId],
                    ['variant_id', '=', (int)$orderDetail['variantId']]
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
            foreach($store_inventory as $storeInventory){
                $id = $storeInventory->id;
                $store = StoreInventory::find($id);

                $orderQty = $orderDetail['quantity'];
                $stockQty = $store->onHand;
                $productOutgoing = $store->productOutgoing;


                $store->onHand = $stockQty - $orderQty;
                $store->productOutgoing = $productOutgoing + $orderQty;
                $store->save();

            }

            if($orderDetail['type']=='Serialize'){
                foreach ($orderDetail['serialNumbers'] as $serialNumber) {

                    $productSerial = ProductSerial::where([
                        ['productId', (int)$orderDetail['productId']],
                        ['variantId', (int)$orderDetail['variantId']],
                        ['storeId', $request->storeId],
                        ['serialNumber', (int)$serialNumber['serialNumber']],
                        ['saleId', 0]
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
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order created Successfully!'
        ]);
    }

    public function submit(Request $req)
    {

        foreach($req->orders AS $request)
        {
            Session::put('subscriberId', $request['subscriberId']);
            $order = new Order;

            $users = User::where('contact_number', $request['salesBy'])
                ->orWhere('email', $request['salesBy'])
                ->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No User'
                ]);
            } else {
                foreach ($users as $user) {
                    $userId =  $user->id;
                    $userName = $user->name;
                }
            }

            $order->orderId         = $request['orderId'];
            $order->clientId        = (int)$request['clientId'];
            $order->total           = $request['total'];
            $order->totalDiscount   = $request['totalDiscount'];
            $order->specialDiscount = $request['specialDiscount'];
            $order->grandTotal      = $request['grandTotal'];
            $order->subscriber_id   = (int)$request['subscriberId'];
            $order->store_id        = (int)$request['storeId'];
            // $order->pos_id          = (int)$request['posId'];
            $order->salesBy_id      = (int)$userId;
            $orderDateStr           = strtotime($request['orderDate']);
            $order->orderDate       = date('Y-m-d', $orderDateStr);
            $order->totalTax        = $request['totalTax'];
            $order->created_by      = (int)$request['subscriberId'];

            $order->save();

            if ($request['clientId'] == "") {
                $clientId = 0;
            } else {
                $clientId = (int)$request['clientId'];
            }

                $deposit = new Deposit;
                $depositDate                = strtotime($request['orderDate']);
                $deposit->deposit_date      = date('Y-m-d', $depositDate);

                $paidAmount = doubleval($request['grandTotal']) - doubleval($request['due']);

                $deposit->due               = $request['due'];
                $deposit->deposit           = $paidAmount;
                $deposit->deposit_type      = 'Manual';
                $lastBalance = DB::table('deposits')->where('client_id', $clientId)->orderBy('id', 'desc')->limit(1)->first();

                if ($lastBalance === null) {
                    $lastBalance = 0;
                    $deposit->balance           = $lastBalance + $paidAmount - $request['grandTotal'];
                } else {
                    $deposit->balance           = $lastBalance->balance + $paidAmount - $request['grandTotal'];
                }
                $deposit->status            = 'App deposit';
                $deposit->order_id          = $order->orderId;

            $deposit->client_id         = $clientId;
            $deposit->store_id          = (int)$request['storeId'];
            $deposit->salesBy_id        = (int)$userId;
            $deposit->salesBy_name      = $userName;
            $deposit->subscriber_id     = (int)$request['subscriberId'];

            $deposit->save();

            $totalPaid = 0;
            $checkXX = 0;
            foreach ($request['paymentDetails'] as $paymentDetail) {

                $payment = new Payment;
                if ($checkXX == 0) {
                    if ($paymentDetail['type'] == 'cash') {
                        $payment->cash     = $paymentDetail['amount'];
                    } elseif ($paymentDetail['type'] == 'card') {

                        $payment->card     = $paymentDetail['amount'];
                    }
                    else{
                        $payment->mobile_bank     = $paymentDetail['amount'];
                        $payment->mobile_bank_type     = $paymentDetail['id'];
                    }
                    $payment->orderId       = $order->id;
                    $payment->clientId      = $clientId;
                    $payment->subscriber_id = (int)$request['subscriberId'];
                    $payment->due = doubleval($request['due']);

                    $payment->total = $request['grandTotal'];
                    $payment->save();
                    $paymentId = $payment->id;
                } else {

                    $payment = Payment::find($paymentId);
                    $due = $payment->due;
                    if ($paymentDetail['type'] == 'cash') {
                        $payment->cash     = $paymentDetail['amount'];
                    } elseif ($paymentDetail['type'] == 'card') {
                        $payment->card     = $paymentDetail['amount'];
                    } else {
                        $payment->mobile_bank     = $paymentDetail['amount'];
                        $payment->mobile_bank_type     = $paymentDetail['id'];
                    }
                    $payment->save();
                }
                $checkXX++;
            }

            foreach ($request['orderDetails'] as $orderDetail) {

                $orderedProduct = new OrderedProduct;

                $orderedProduct->productId          = (int)$orderDetail['productId'];
                $orderedProduct->variant_id          = (int)$orderDetail['variantId'];
                $orderedProduct->productName        = $orderDetail['productName'];
                $orderedProduct->quantity           = $orderDetail['quantity'];
                $orderedProduct->offerItemId        = (int)$orderDetail['offerItemId'];
                $orderedProduct->offerName          = $orderDetail['offerName'];
                $orderedProduct->offerQuantity      = $orderDetail['offerQuantity'];
                $orderedProduct->specialDiscount    = $request['specialDiscount'];
                $orderedProduct->totalDiscount      = $orderDetail['totalDiscount'];
                $orderedProduct->totalPrice         = $orderDetail['totalPrice'];
                $orderedProduct->grandTotal         = $orderDetail['grandTotal'];
                $orderedProduct->totalTax           = $orderDetail['totalTax'];
                $orderedProduct->orderId            = $order->id;
                $orderedProduct->subscriber_id      = (int)$request['subscriberId'];
                $orderedProduct->salesBy_id         = (int)$userId;

                foreach ($orderDetail['discountDetails'] as $discountDetail) {

                    $orderedProductDiscount = new OrderedProductDiscount;
                    $orderedProductDiscount->discountId         = (int)$discountDetail['id'];
                    $orderedProductDiscount->type               = $discountDetail['discountType'];
                    $orderedProductDiscount->discountAmount     = $discountDetail['discount'];
                    $orderedProductDiscount->discountName       = $discountDetail['discountName'];
                    $orderedProductDiscount->orderId            = $order->id;
                    $orderedProductDiscount->productId          = (int)$orderDetail['productId'];
                    $orderedProductDiscount->created_by = (int)$request['subscriberId'];
                    $orderedProductDiscount->save();
                }

                foreach ($orderDetail['tax'] as $tax) {
                    $orderedProductTax = new OrderedProductTax;

                    $orderedProductTax->taxId       = (int)$tax['id'];
                    $orderedProductTax->taxName     = $tax['taxName'];
                    $orderedProductTax->taxAmount   = $tax['taxAmount'];
                    $orderedProductTax->orderId     = $order->id;
                    $orderedProductTax->productId   = (int)$orderDetail['productId'];
                    $orderedProductTax->created_by = (int)$request['subscriberId'];
                    $orderedProductTax->save();
                }

                $orderedProduct->created_by = (int)$request['subscriberId'];
                $orderedProduct->save();
            }

            foreach ($request['orderDetails'] as $orderDetail) {
                $store_inventory = StoreInventory::where([
                    ['store_id', '=', $request['storeId']],
                    ['productId', '=', (int)$orderDetail['productId']]
                ])
                    ->orderBy('created_at', 'asc')
                    ->get();

                $orderId = $request['orderId'];

                $offerQuantity = (int)$orderDetail['offerQuantity'];
                $offerItemId = (int)$orderDetail['offerItemId'];

                if ($offerQuantity > 0) {
                    $offer_store_inventory = StoreInventory::where([
                        ['store_id', '=', $request['storeId']],
                        ['productId', '=', $offerItemId]
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
                foreach($store_inventory as $storeInventory){
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

            $totalPaid = doubleval($request['cash']) + doubleval($request['card']) + doubleval($request['mobile_bank']);
            $totalDue = doubleval($request['due']);
            $total = $totalPaid + $totalDue;
            $cash = doubleval($request['cash']);
            $card = doubleval($request['card']);
            $mobileBank = doubleval($request['mobile_bank']);
            $date = strtotime($request['orderDate']);

            $duePayment = new DuePayment;
            $duePayment->total              = $total;
            $duePayment->paid_amount        = $totalPaid;
            $duePayment->due_amount         = $totalDue;
            $duePayment->payment_method     = "App sale mix payment";
            $duePayment->cash               = $cash;
            $duePayment->card               = $card;
            $duePayment->mobile_bank        = $mobileBank;
            $duePayment->clientId           = $clientId;
            $duePayment->subscriber_id      = (int)$request['subscriberId'];
            $duePayment->salesBy            = $request['salesBy'];
            $duePayment->salesBy_name       = $userName;
            $duePayment->store_id           = (int)$request['storeId'];
            $duePayment->note               = "App Sale Due";
            $duePayment->deposit_date      = date('Y-m-d', $date);
            $duePayment->save();
        }
        return response()->json([
            'status' => 200,
            'message' => 'Order created Successfully!'
        ]);
    }

    public static function nextBatch($store_inventory, $excessQty, $orderId){
        foreach($store_inventory as $storeInventory){
            $batchNumber = $storeInventory->batch_number;

            if($batchNumber != 0){
                if($excessQty > 0){
                    $id = $storeInventory->id;
                    $store = StoreInventory::find($id);
                    $stockQty = $store->onHand;
                    $productOutgoing = $store->productOutgoing;

                    if($stockQty == 0){
                        return response() -> json([
                            'status'=>200,
                            'orderId'=> $orderId,
                            'message' => 'Stock Out!!!----'
                        ]);
                    }elseif($stockQty > $excessQty){
                        $store->onHand = $stockQty - $excessQty;
                        $store->productOutgoing = $productOutgoing + $excessQty;
                        $store->save();

                        return response() -> json([
                            'status'=>200,
                            'orderId'=> $orderId,
                            'testData' => $excessQty,
                            'message' => 'Order created successfully from batch '.$batchNumber
                        ]);
                    }
                }
            }
        }
    }

    public function list(Request $request, $subscriberId, $date){

        $data = Order::leftjoin('clients', 'clients.id', 'orders.clientId')
        ->orderBy('orders.id', 'desc')
        ->whereDate('orders.orderDate', $date)
        ->where('orders.subscriber_id', $subscriberId)
        ->get(['orders.*', 'clients.name', 'clients.mobile']);


            return response()->json([
                'status'=>200,
                'date'=>$data,
            ]);



    }

    public function productList(Request $request, $subscriberId, $orderId){

        $productList = OrderedProduct::where([['subscriber_id', $subscriberId], ['orderId', $orderId]])->get();

            return response()->json([
                'status'=>200,
                'productList'=>$productList,
            ]);


    }
}
