<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Price;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Supplier;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\PurchaseReturn;
use App\Models\StoreInventory;
use Illuminate\Support\Carbon;
use App\Models\PurchaseProduct;
use App\Models\PurchaseProductList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PurchaseController extends Controller
{
    public function create(){
        $products = Product::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $batches = Batch::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();


        // return $products;
        return view('purchase/purchase', ['products' => $products, 'suppliers' => $suppliers, 'stores' => $stores, 'batches' => $batches,'variants' => $variants]);
    }

    public function store(Request $request){
        // Log::info($request);
        $messages = [
            'poNumber.required'    =>   "P.O. Number is required.",
            'poNumber.unique'    =>   "P.O. Number must be unique."
        ];

        $validator = Validator::make($request->all(), [
            'poNumber'    => 'unique:purchase_products|required'
        ], $messages);

        if ($validator->passes()) {
        $purchaseProduct = new PurchaseProduct;

        $purchaseProduct->supplierId    = (int)$request->supplierId;
        $purchaseProduct->store         = $request->store;
        $purchaseProduct->poNumber      = $request->poNumber;
        $purchaseProduct->totalPrice    = doubleval($request->totalPrice);
        $purchaseProduct->discount      = doubleval($request->discount);
        $purchaseProduct->other_cost    = doubleval($request->otherCost);
        $purchaseProduct->grandTotal    = doubleval($request->grandTotal);

        $purchaseDateStr                = strtotime($request->purchaseDate);
        $purchaseProduct->purchaseDate  = date('Y-m-d', $purchaseDateStr);
        $purchaseProduct->purchaseNote  = $request->purchaseNote;
        $purchaseProduct->subscriber_id	= Auth::user()->subscriber_id;

        $purchaseProduct->save();

        foreach($request->productList as $product){
            $products = new PurchaseProductList;

            $products->productId             = (int)$product['productId'];
            $products->productName           = $product['productName'];
            $products->quantity              = doubleval($product['quantity']);
            $products->unitPrice             = doubleval($product['unitPrice']);
            $products->mrp                   = doubleval($product['mrp']);
            $products->totalPrice            = doubleval($product['totalPrice']);
            $products->variant_id            = $product['variantId'];
            $products->variant_name          = $product['variant'];
            $products->purchaseProductId     = $purchaseProduct->id;

            $products->save();
        }

        return response() -> json([
            'status'=>400,
            'message' => 'Purchase created Successfully!'
        ]);
    }
        return response()->json([
            'error' => $validator->errors(),
            'status' => 200,
            'message' => 'Purchase created Unsuccessfully!'
        ]);
    }

    public function listView(Request $request){
        $purchase = PurchaseProduct::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $suppliers = Supplier::all();

        return view('purchase/purchase-list', ['purchase' => $purchase], compact('suppliers'));
    }

    public function list(Request $request, $startdate, $enddate){

        // $purchase = PurchaseProduct::where('subscriber_id', Auth::user()->subscriber_id)->orderBy('created_at', 'desc')->get();

        $purchase = PurchaseProduct::join('suppliers', 'purchase_products.supplierId', '=', 'suppliers.id')
        ->whereBetween('purchase_products.purchaseDate', [$startdate, $enddate])
        ->where('purchase_products.subscriber_id', Auth::user()->subscriber_id)->orderBy('purchase_products.created_at', 'desc')
        ->get(['suppliers.name', 'purchase_products.*']);

        foreach ($purchase as $p) {
            $purchaseReturn = PurchaseReturn::where('po_no', $p->poNumber)->first();
            if ($purchaseReturn != null) {
                $p['purchaseReturn'] = $purchaseReturn;
            } else {
                $p['purchaseReturn'] = 0.00;
            }
        }
        if ($request->ajax()) {
            return response()->json([
                'purchase' => $purchase,
            ]);
        }
    }

    public function listData(Request $request, $supplier_id, $startdate, $enddate)
    {
        $purchase = PurchaseProduct::join('suppliers', 'purchase_products.supplierId', '=', 'suppliers.id')
        ->whereBetween('purchase_products.purchaseDate', [$startdate, $enddate])
            ->where(
                // ['purchase_products.subscriber_id', '=', Auth::user()->subscriber_id],
                // ['purchase_products.supplierId', '=', $supplier_id],
                'suppliers.id','=', $supplier_id
            )
            ->orderBy('purchase_products.created_at', 'desc')
            ->get(['suppliers.name', 'purchase_products.*']);


        foreach ($purchase as $p) {
            $purchaseReturn = PurchaseReturn::where('po_no', $p->poNumber)->first();
            if ($purchaseReturn != null) {
                $p['purchaseReturn'] = $purchaseReturn;
            } else {
                $p['purchaseReturn'] = 0.00;
            }
        }
        if ($request->ajax()) {
            return response()->json([
                'status' => 200,
                'purchase' => $purchase,
                'message' => 'Success!'
            ]);
        }
    }

    public function productListView(Request $request, $id){
        $productList = PurchaseProductList::where([
                    ['purchaseProductId', $id]
                ])
                ->get();

        $purchaseList = PurchaseProduct::where([
                    ['id', $id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->get();

        foreach($purchaseList as $purchase){
            $p =  $purchase->supplierId;
        }

        $supplier = Supplier::where('id', $p)->get();

         return view('purchase/purchaseproduct-list', ['productList' => $productList, 'purchaseList' => $purchaseList, 'supplier' => $supplier]);
    }

    public function productList(Request $request, $id){
        $productList = PurchaseProductList::where('purchaseProductId', $id)->get();
        $purchaseList = PurchaseProduct::where([
                    ['id', $id],
                    ['subscriber_id', Auth::user()->subscriber_id]
                ])
                ->get();

        foreach($purchaseList as $purchase){
            $p =  $purchase->supplierId;
        }

        $supplier = Supplier::where('id', $p)->get();

        if($request -> ajax()){
            return response()->json([
                'productList'=>$productList,
            ]);
        }


    }

    public function productEdit(Request $request, $id){
        $productList = PurchaseProductList::where('purchaseProductId', $id)->get();
        $purchaseList = PurchaseProduct::where('id', $id)->get();

        foreach($purchaseList as $purchase){
            $p =  $purchase->supplierId;
        }

        $supplier = Supplier::where('id', $p)->get();
        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'status'=>200,
                'purchaseList'=>$purchaseList,
                'productList'=>$productList,
                'supplier'=>$supplier,
            ]);
        }

        return view('purchase/purchaseproduct-edit', ['productList' => $productList, 'purchaseList' => $purchaseList, 'supplier' => $supplier, 'suppliers' => $suppliers, 'stores' => $stores]);

    }

    public function update(Request $request, $id){
        // log::info($request);
        // log::info($request->input());
        // log::info($request->input('grandtotalprice'));
        // log::info($request->input('subtotalprice'));
        // log::info($request->input('totalprice' . 63));
        $purchaseProduct = PurchaseProduct::find($id);

        if($request->subtotalprice > 0)
        {
        $purchaseProduct->supplierId        = $request->supplier;
        $purchaseProduct->store             = $request->store;
        $purchaseProduct->purchaseDate      = $request->purchasedate;
        $purchaseProduct->purchaseNote      = $request->note;

        $purchaseProduct->discount        = $request->discount;
        $purchaseProduct->other_cost        = $request->othercost;

        $purchaseProduct->totalPrice         = $request->subtotalprice;
        $purchaseProduct->grandTotal        = $request->grandtotalprice;

        $purchaseProduct->save();
        }
        else {
            // log::info('delete purchase');
            $purchaseProduct->delete($id);
        }

        $purchaseProductList = PurchaseProductList::where('purchaseProductId', $id)->get(); //get purchase products using the purchase id
        // $purchaseList = PurchaseProduct::where('id', $id)->get('discount');

        // $total = 0;

        foreach($purchaseProductList as $product){                                          //getting individual products of purchase
            $pid = $product->id;                                                            //getting id of first product

            $ppl = PurchaseProductList::find($pid);                                         //finding the row of product using product id

            if( $request->input('subtotalprice') == 0 ){
                // log::info('delete product');
                $ppl->delete($pid);
            }else{
                $ppl->quantity      =   $request->input('editquantity'.$pid);
                $ppl->totalPrice    =   $request->input('totalprice'.$pid);

                // $total = $total + $request->input('edittotalprice'.$pid);                       //changing the total price with modified product price

                // $purchaseProduct->totalPrice = $total;

                // foreach($purchaseList as $purchase){                                            //getting discount from purchase list
                //     $dis =  $purchase->discount;
                // }

                // $purchaseProduct->grandTotal = $total - $dis + $purchaseProduct->other_cost;                                   //substracting discount from total price for calculating the grandTotal

                // $purchaseProduct->save();

                $ppl->save();
            }

        }

        return response() -> json([
            'status'=>200,
            'message' => 'Data Updated Successfully'
        ]);
    }

    public function productReceive(Request $request, $id){
        // Log::info($request);

        if($request->onlyNonSerilize!='yes'){
            $serilize=0;
            $nonSerilize=0;
            $nonSerializeRecieve=0;
            foreach($request->productList as $product){
                $productId = $product['productId'];
                $productType = Product::find($productId);
                if($productType->type=='Serialize'){
                    $serilize=1;
                }
                else{
                    $nonSerilize=1;
                    if($product['quantity']==$product['recieve_quantity']){
                        $nonSerializeRecieve=1;
                    }
                }
            }

            if($serilize==1&&$nonSerilize==0)
            {
                return response() -> json([
                    'status'=>400,
                    'error' => 'Serialize Product'
                ]);
            }
            elseif($serilize==1&&$nonSerilize==1){
                return response() -> json([
                    'status'=>400,
                    'error' => 'Non-Serialize Product',
                    'nonSerializeRecieve'=>$nonSerializeRecieve
                ]);
            }
        }
        $purchase = PurchaseProduct::find($id);
        // $purchase->status = 'received';

        // Log::info($request->store);

        $storeName = $request->store;
        $recieved=0;

        if($storeName =="Warehouse"){
            foreach($request->productList as $product){
                $productId = $product['productId'];
                $variantId = $product['variantId'];

                $productType = Product::find($productId);
                if($productType->barcode==''){
                    $barcode='N/A';
                }
                else{
                    $barcode=$productType->barcode;
                }
                if($productType->type!='Serialize'){
                    $productSerial = ProductSerial::where([
                            ['productId', $productId],
                            ['variantId', $variantId],
                            // ['purchaseId', $request->purchaseId],
                            ['storeId', 0],
                            // ['serialNumber', $productType->barcode]
                        ])->get();

                    if($productSerial->isEmpty()){
                        $productSerial = new ProductSerial;
                        $productSerial->productId = doubleval($productId);
                        $productSerial->productName = $product['productName'];
                        $productSerial->variantId = doubleval($variantId);
                        $productSerial->variantName = $product['variant'];
                        $productSerial->storeId = 0;
                        $productSerial->serialNumber = $barcode;
                        $productSerial->purchaseId =  $id;

                        $todayDate = Carbon::today();
                        $productSerial->purchaseDate  = $todayDate;

                        $productSerial->created_by = Auth::user()->name;
                        $productSerial->subscriber_id =  Auth::user()->subscriber_id;

                        $productSerial->save();
                    }
                    else{
                        foreach($productSerial AS $serial){
                            $productSerialId=$serial->id;
                        }
                        $productSerial=ProductSerial::find($productSerialId);
                        if($barcode!='N/A' || $productSerial->serialNumber==''){
                            $productSerial->serialNumber = $barcode;
                            $productSerial->save();
                        }
                    }
                    // $serialNumber=$productType->barcode;


                    $inventories = Inventory::where([
                        ['productId', $productId],
                        ['variant_id', $variantId]
                    ])->get();

                    if($inventories->isEmpty()){

                        $inventories = new Inventory;
                        $inventories->onHand = doubleval($product['quantity']);
                        $inventories->productIncoming = doubleval($product['quantity']);
                        // $inventories->safety_stock = doubleval($product['quantity']);
                        $inventories->mrp = $product['mrp'];
                        $inventories->price = $product['unitPrice'];
                        $inventories->productId = $product['productId'];
                        $inventories->variant_id =  $product['variantId'];
                        $inventories->variant_name =  $product['variant'];

                        $inventories->save();

                        $price = new Price;
                        $price->product_id = $product['productId'];
                        $price->price = $product['unitPrice'];
                        $price->mrp = $product['mrp'];
                        $price->quantity = doubleval($product['quantity']);
                        $price->subscriber_id = Auth::user()->subscriber_id;
                        $price->store_id = 0;
                        $price->variant_id = $product['variantId'];
                        $price->variant_name = $product['variant'];
                        $price->product_in_num = 0;

                        $price->save();
                        $purchase->save();
                    }
                    else{
                        foreach($inventories as $inventory){
                            $inventoryId = $inventory->id;
                        }

                        $inventoryX = Inventory::find($inventoryId);

                        $onHand = $inventoryX->onHand;
                        $productIncoming = $inventoryX->productIncoming;
                        $mrp = $inventoryX->mrp;
                        $price = $inventoryX->price;

                        $purchaseQuantity = doubleval($product['quantity'])-doubleval($product['recieve_quantity']);
                        $purchaseMrp = doubleval($product['mrp']);
                        $purchaseUnitPrice = doubleval($product['unitPrice']);

                        $inventoryX->onHand = $onHand + $purchaseQuantity;
                        $inventoryX->productIncoming = $productIncoming + $purchaseQuantity;

                        if($mrp != $purchaseMrp || $price != $purchaseUnitPrice){

                            $price = new Price;
                            $price->product_id = $product['productId'];
                            $price->price = $product['unitPrice'];
                            $price->mrp = $product['mrp'];
                            $price->quantity = doubleval($product['quantity']);
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->store_id = 0;
                            $price->variant_id = $product['variantId'];
                            $price->variant_name = $product['variant'];
                            $price->product_in_num = 0;

                            $price->save();

                            $inventoryX->mrp = $purchaseMrp;
                            $inventoryX->price = $purchaseUnitPrice;
                            $inventoryX->save();
                        }

                        $inventoryX->save();
                        $purchase->save();
                    }
                    $purchaseProduct = PurchaseProductList::find($product['id']);
                    $purchaseProduct->recieve_quantity = doubleval($product['quantity']);
                    $purchaseProduct->save();
                }
                elseif($productType->type=='Serialize' && $product['quantity']!=$product['recieve_quantity']){
                    $recieved=1;
                }
            }
            // Log::info('Inventory '.$inventoryId);
        }else{
            $stores = Store::where('store_name', $storeName)->get();
            foreach($stores as $store){
                $storeId = $store->id;
            }
            // Log::info($storeName);

            foreach($request->productList as $product){
                $productId = $product['productId'];
                $variantId = $product['variantId'];

                $productType = Product::find($productId);
                if($productType->barcode==''){
                    $barcode='N/A';
                }
                else{
                    $barcode=$productType->barcode;
                }
                if($productType->type!='Serialize'){
                    $productSerial = ProductSerial::where([
                            ['productId', $productId],
                            ['variantId', $variantId],
                            // ['purchaseId', $request->purchaseId],
                            ['storeId', $storeId],
                            // ['serialNumber', $productType->barcode]
                        ])->get();

                    if($productSerial->isEmpty()){
                        $productSerial = new ProductSerial;
                        $productSerial->productId = doubleval($productId);
                        $productSerial->productName = $product['productName'];
                        $productSerial->variantId = doubleval($variantId);
                        $productSerial->variantName = $product['variant'];
                        $productSerial->storeId = $storeId;
                        $productSerial->serialNumber = $barcode;
                        $productSerial->purchaseId =  $id;

                        $todayDate = Carbon::today();
                        $productSerial->purchaseDate  = $todayDate;

                        $productSerial->created_by = Auth::user()->name;
                        $productSerial->subscriber_id =  Auth::user()->subscriber_id;

                        $productSerial->save();
                    }
                    else{
                        foreach($productSerial AS $serial){
                            $productSerialId=$serial->id;
                        }
                        $productSerial=ProductSerial::find($productSerialId);
                        if($barcode!='N/A' || $productSerial->serialNumber==''){
                            $productSerial->serialNumber = $barcode;
                            $productSerial->save();
                        }
                    }

                    if( $variantId == 0){
                        $storeInventories = StoreInventory::where([
                            ['productId', $productId],
                            ['store_id', $storeId],
                            ['variant_id', 0]
                        ])
                        ->get();

                        if($storeInventories->isEmpty()){

                            $storeInventoryN = new StoreInventory;
                            $storeInventoryN->onHand = doubleval($product['quantity']);
                            $storeInventoryN->productIncoming = doubleval($product['quantity']);
                            // $storeInventoryN->safety_stock = doubleval($product['quantity']);
                            $storeInventoryN->mrp = $product['mrp'];
                            $storeInventoryN->price = $product['unitPrice'];
                            $storeInventoryN->productId = $product['productId'];
                            $storeInventoryN->store_id = $storeId;
                            $storeInventoryN->variant_id =  $product['variantId'];
                            $storeInventoryN->variant_name =  $product['variant'];

                            $storeInventoryN->save();

                            $price = new Price;
                            $price->product_id = $product['productId'];
                            $price->price = $product['unitPrice'];
                            $price->mrp = $product['mrp'];
                            $price->quantity = doubleval($product['quantity']);
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->store_id = $storeId;
                            $price->variant_id = $product['variantId'];
                            $price->variant_name = $product['variant'];
                            $price->product_in_num = 0;

                            $price->save();
                            $purchase->save();

                        }else{
                            foreach($storeInventories as $storeInventory){
                                $storeInventoryId = $storeInventory->id;
                            }

                            $storeInventoryX = StoreInventory::find($storeInventoryId);

                            $onHand = $storeInventoryX->onHand;
                            $productIncoming = $storeInventoryX->productIncoming;
                            $mrp = $storeInventoryX->mrp;
                            $price = $storeInventoryX->price;

                            $purchaseQuantity = doubleval($product['quantity'])-doubleval($product['recieve_quantity']);
                            $purchaseMrp = doubleval($product['mrp']);
                            $purchaseUnitPrice = doubleval($product['unitPrice']);

                            $storeInventoryX->onHand = $onHand + $purchaseQuantity;
                            $storeInventoryX->productIncoming = $productIncoming + $purchaseQuantity;

                            if($mrp != $purchaseMrp || $price != $purchaseUnitPrice){

                                $price = new Price;
                                $price->product_id = $product['productId'];
                                $price->price = $product['unitPrice'];
                                $price->mrp = $product['mrp'];
                                $price->quantity = doubleval($product['quantity']);
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->store_id = $storeId;
                                $price->variant_id = $product['variantId'];
                                $price->variant_name = $product['variant'];
                                $price->product_in_num = 0;

                                $price->save();

                                $storeInventoryX->mrp = $purchaseMrp;
                                $storeInventoryX->price = $purchaseUnitPrice;
                                $storeInventoryX->save();
                            }

                            $storeInventoryX->save();
                            $purchase->save();
                        }

                        // Log::info('batch0 '.$storeInventories);

                    }else{

                        $storeInventories = StoreInventory::where([
                            ['productId', $productId],
                            ['store_id', $storeId],
                            ['variant_id', $variantId]
                        ])
                        ->get();

                        if($storeInventories->isEmpty()){

                            $storeInventoryN = new StoreInventory;
                            $storeInventoryN->onHand = doubleval($product['quantity']);
                            $storeInventoryN->productIncoming = doubleval($product['quantity']);
                            // $storeInventoryN->safety_stock = doubleval($product['quantity']);
                            $storeInventoryN->mrp = $product['mrp'];
                            $storeInventoryN->price = $product['unitPrice'];
                            $storeInventoryN->productId = $product['productId'];
                            $storeInventoryN->store_id = $storeId;
                            $storeInventoryN->variant_id =  $product['variantId'];
                            $storeInventoryN->variant_name =  $product['variant'];

                            $storeInventoryN->save();

                            $price = new Price;
                            $price->product_id = $product['productId'];
                            $price->price = $product['unitPrice'];
                            $price->mrp = $product['mrp'];
                            $price->quantity = doubleval($product['quantity']);
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->store_id = $storeId;
                            $price->variant_id = $product['variantId'];
                            $price->variant_name = $product['variant'];
                            $price->product_in_num = 0;

                            $price->save();
                            $purchase->save();

                        }else{

                            foreach($storeInventories as $storeInventory){
                                $storeInventoryId = $storeInventory->id;
                            }

                            $storeInventoryX = StoreInventory::find($storeInventoryId);

                            $onHand = $storeInventoryX->onHand;
                            $productIncoming = $storeInventoryX->productIncoming;
                            $mrp = $storeInventoryX->mrp;
                            $price = $storeInventoryX->price;

                            $purchaseQuantity = doubleval($product['quantity'])-doubleval($product['recieve_quantity']);
                            $purchaseMrp = doubleval($product['mrp']);
                            $purchaseUnitPrice = doubleval($product['unitPrice']);

                            $storeInventoryX->onHand = $onHand + $purchaseQuantity;
                            $storeInventoryX->productIncoming = $productIncoming + $purchaseQuantity;

                            if($mrp != $purchaseMrp || $price != $purchaseUnitPrice){

                                $price = new Price;
                                $price->product_id = $product['productId'];
                                $price->price = $product['unitPrice'];
                                $price->mrp = $product['mrp'];
                                $price->quantity = doubleval($product['quantity']);
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->store_id = $storeId;
                                $price->variant_id = $product['variantId'];
                                $price->variant_name = $product['variant'];
                                $price->product_in_num = 0;

                                $price->save();

                                $storeInventoryX->mrp = $purchaseMrp;
                                $storeInventoryX->price = $purchaseUnitPrice;
                                $storeInventoryX->save();
                            }

                            $storeInventoryX->save();
                            $purchase->save();

                        }
                    }

                    $purchaseProduct = PurchaseProductList::find($product['id']);
                    $purchaseProduct->recieve_quantity = doubleval($product['quantity']);
                    $purchaseProduct->save();
                }
                elseif($productType->type=='Serialize' && $product['quantity']!=$product['recieve_quantity']){
                    $recieved=1;
                }
            }
        }

        if($recieved==0)
        {
            $purchase = PurchaseProduct::find($id);
            $purchase->status = 'received';
            $purchase->save();
        }
        else if($recieved==1)
        {
            $purchase = PurchaseProduct::find($id);
            $purchase->status = 'partial recieved';
            $purchase->save();
        }
        return response() -> json([
            'status'=>200,
            'message' => 'Received Successfully'
        ]);
    }

    public function productPartialReceive(Request $request, $id){

        // Log::info($request);
        $storeName = $request->store;

        if($storeName =="Warehouse"){
            foreach($request->productList as $product){
                $productId = $product['productId'];
                $variantId = $product['variantId'];

                $productType = Product::find($productId);
                if($productType->type=='Serialize'){
                    // $productSerial = ProductSerial::where([
                    //     ['productId', $productId],
                    //     ['variantId', $variantId],
                    //     ['purchaseId', $request->purchaseId],
                    //     ['storeId', 0]
                    // ])->get();

                    // if($productSerial->isNotEmpty()){
                    //     $serialNumber=$productSerial->serialNumber;
                    // }
                    // else{
                        // }
                    if($request->serialNumbers!=$product['recieve_quantity']){
                        return response() -> json([
                            'status'=>400,
                            'error' => 'Serial number needed'
                        ]);
                    }
                }
                else{
                    $productSerial = ProductSerial::where([
                            ['productId', $productId],
                            ['variantId', $variantId],
                            ['purchaseId', $request->purchaseId],
                            ['storeId', 0],
                            ['serialNumber', $productType->barcode]
                        ])->get();

                    if($productSerial->isEmpty()){
                        $productSerial = new ProductSerial;
                        $productSerial->productId = doubleval($productId);
                        $productSerial->productName = $product['productName'];
                        $productSerial->variantId = doubleval($variantId);
                        $productSerial->variantName = $product['variant'];
                        $productSerial->storeId = 0;
                        $productSerial->serialNumber = $productType->barcode;
                        $productSerial->purchaseId =  doubleval($request->purchaseId);

                        $purchaseDateStr                = strtotime($request->purchaseDate);
                        $productSerial->purchaseDate  = date('Y-m-d', $purchaseDateStr);

                        $productSerial->created_by = Auth::user()->name;
                        $productSerial->subscriber_id =  Auth::user()->subscriber_id;

                        $productSerial->save();
                    }
                    // $serialNumber=$productType->barcode;
                }


                $inventories = Inventory::where([
                    ['productId', $productId],
                    ['variant_id', $variantId]
                ])->get();

                $purchaseProduct = PurchaseProductList::find($id);
                $quantity = $purchaseProduct->quantity;
                $recieve_quantity = $purchaseProduct->recieve_quantity+doubleval($product['recieve_quantity']);
                if($quantity<$recieve_quantity)
                {
                    return response() -> json([
                        'status'=>400,
                        'error' => 'Purchase quantity not available'
                    ]);
                }
                else
                {
                    if($inventories->isEmpty()){

                        $inventories = new Inventory;
                        $inventories->onHand = doubleval($product['recieve_quantity']);
                        $inventories->productIncoming = doubleval($product['recieve_quantity']);
                        // $inventories->safety_stock = doubleval($product['quantity']);
                        $inventories->mrp = $product['mrp'];
                        $inventories->price = $product['unitPrice'];
                        $inventories->productId = $product['productId'];
                        $inventories->variant_id =  $product['variantId'];
                        $inventories->variant_name =  $product['variant'];

                        $inventories->save();

                        $price = new Price;
                        $price->product_id = $product['productId'];
                        $price->price = $product['unitPrice'];
                        $price->mrp = $product['mrp'];
                        $price->quantity = doubleval($product['recieve_quantity']);
                        $price->subscriber_id = Auth::user()->subscriber_id;
                        $price->store_id = 0;
                        $price->variant_id = $product['variantId'];
                        $price->variant_name = $product['variant'];
                        $price->product_in_num = 0;

                        $price->save();
                        // $purchase->save();
                    }
                    else{
                        foreach($inventories as $inventory){
                            $inventoryId = $inventory->id;
                        }

                        $inventoryX = Inventory::find($inventoryId);

                        $onHand = $inventoryX->onHand;
                        $productIncoming = $inventoryX->productIncoming;
                        $mrp = $inventoryX->mrp;
                        $price = $inventoryX->price;

                        $purchaseQuantity = doubleval($product['recieve_quantity']);
                        $purchaseMrp = doubleval($product['mrp']);
                        $purchaseUnitPrice = doubleval($product['unitPrice']);

                        $inventoryX->onHand = $onHand + $purchaseQuantity;
                        $inventoryX->productIncoming = $productIncoming + $purchaseQuantity;

                        if($mrp != $purchaseMrp || $price != $purchaseUnitPrice){

                            $price = new Price;
                            $price->product_id = $product['productId'];
                            $price->price = $product['unitPrice'];
                            $price->mrp = $product['mrp'];
                            $price->quantity = doubleval($product['recieve_quantity']);
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->store_id = 0;
                            $price->variant_id = $product['variantId'];
                            $price->variant_name = $product['variant'];
                            $price->product_in_num = 0;

                            $price->save();

                            $inventoryX->mrp = $purchaseMrp;
                            $inventoryX->price = $purchaseUnitPrice;
                            $inventoryX->save();
                        }

                        $inventoryX->save();
                        // $purchase->save();
                    }
                    $purchaseProduct->recieve_quantity = $recieve_quantity;
                    $purchaseProduct->save();
                }
            }
            // Log::info('Inventory '.$inventoryId);

        }else{
            $stores = Store::where('store_name', $storeName)->get();
            foreach($stores as $store){
                $storeId = $store->id;
            }
            // Log::info($storeName);

            foreach($request->productList as $product){
                $productId = $product['productId'];
                $variantId = $product['variantId'];

                $productType = Product::find($productId);
                if($productType->type=='Serialize'){
                    // $productSerial = ProductSerial::where([
                    //     ['productId', $productId],
                    //     ['variantId', $variantId],
                    //     ['purchaseId', $request->purchaseId],
                    //     ['storeId', $storeId]
                    // ])->get();

                    // if($productSerial->isNotEmpty()){
                    //     $serialNumber=$productSerial->serialNumber;
                    // }
                    // else{
                        // }
                    if($request->serialNumbers!=$product['recieve_quantity']){
                        return response() -> json([
                            'status'=>400,
                            'error' => 'Serial number needed'
                        ]);
                    }
                }
                else{
                    $productSerial = ProductSerial::where([
                            ['productId', $productId],
                            ['variantId', $variantId],
                            ['purchaseId', $request->purchaseId],
                            ['storeId', $storeId],
                            ['serialNumber', $productType->barcode]
                        ])->get();

                    if($productSerial->isEmpty()){
                        $productSerial = new ProductSerial;
                        $productSerial->productId = doubleval($productId);
                        $productSerial->productName = $product['productName'];
                        $productSerial->variantId = doubleval($variantId);
                        $productSerial->variantName = $product['variant'];
                        $productSerial->storeId = $storeId;
                        $productSerial->serialNumber = $productType->barcode;
                        $productSerial->purchaseId =  doubleval($request->purchaseId);

                        $purchaseDateStr                = strtotime($request->purchaseDate);
                        $productSerial->purchaseDate  = date('Y-m-d', $purchaseDateStr);

                        $productSerial->created_by = Auth::user()->name;
                        $productSerial->subscriber_id =  Auth::user()->subscriber_id;

                        $productSerial->save();
                    }
                    // $serialNumber=$productType->barcode;
                }

                $purchaseProduct = PurchaseProductList::find($id);
                $quantity = $purchaseProduct->quantity;
                $recieve_quantity = $purchaseProduct->recieve_quantity+doubleval($product['recieve_quantity']);
                if($quantity<$recieve_quantity)
                {
                    return response() -> json([
                        'status'=>400,
                        'error' => 'Purchase quantity not available'
                    ]);
                }
                else{
                    if( $variantId == 0){
                        $storeInventories = StoreInventory::where([
                            ['productId', $productId],
                            ['store_id', $storeId],
                            ['variant_id', 0]
                        ])
                        ->get();

                        if($storeInventories->isEmpty()){

                            $storeInventoryN = new StoreInventory;
                            $storeInventoryN->onHand = doubleval($product['recieve_quantity']);
                            $storeInventoryN->productIncoming = doubleval($product['recieve_quantity']);
                            // $storeInventoryN->safety_stock = doubleval($product['quantity']);
                            $storeInventoryN->mrp = $product['mrp'];
                            $storeInventoryN->price = $product['unitPrice'];
                            $storeInventoryN->productId = $product['productId'];
                            $storeInventoryN->store_id = $storeId;
                            $storeInventoryN->variant_id =  $product['variantId'];
                            $storeInventoryN->variant_name =  $product['variant'];

                            $storeInventoryN->save();

                            $price = new Price;
                            $price->product_id = $product['productId'];
                            $price->price = $product['unitPrice'];
                            $price->mrp = $product['mrp'];
                            $price->quantity = doubleval($product['recieve_quantity']);
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->store_id = $storeId;
                            $price->variant_id = $product['variantId'];
                            $price->variant_name = $product['variant'];
                            $price->product_in_num = 0;

                            $price->save();
                            // $purchase->save();

                        }else{
                            foreach($storeInventories as $storeInventory){
                                $storeInventoryId = $storeInventory->id;
                            }

                            $storeInventoryX = StoreInventory::find($storeInventoryId);

                            $onHand = $storeInventoryX->onHand;
                            $productIncoming = $storeInventoryX->productIncoming;
                            $mrp = $storeInventoryX->mrp;
                            $price = $storeInventoryX->price;

                            $purchaseQuantity = doubleval($product['recieve_quantity']);
                            $purchaseMrp = doubleval($product['mrp']);
                            $purchaseUnitPrice = doubleval($product['unitPrice']);

                            $storeInventoryX->onHand = $onHand + $purchaseQuantity;
                            $storeInventoryX->productIncoming = $productIncoming + $purchaseQuantity;

                            if($mrp != $purchaseMrp || $price != $purchaseUnitPrice){

                                $price = new Price;
                                $price->product_id = $product['productId'];
                                $price->price = $product['unitPrice'];
                                $price->mrp = $product['mrp'];
                                $price->quantity = doubleval($product['recieve_quantity']);
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->store_id = $storeId;
                                $price->variant_id = $product['variantId'];
                                $price->variant_name = $product['variant'];
                                $price->product_in_num = 0;

                                $price->save();

                                $storeInventoryX->mrp = $purchaseMrp;
                                $storeInventoryX->price = $purchaseUnitPrice;
                                $storeInventoryX->save();
                            }

                            $storeInventoryX->save();
                            // $purchase->save();
                        }

                        // Log::info('batch0 '.$storeInventories);

                    }else{

                        $storeInventories = StoreInventory::where([
                            ['productId', $productId],
                            ['store_id', $storeId],
                            ['variant_id', $variantId]
                        ])
                        ->get();

                        if($storeInventories->isEmpty()){

                            $storeInventoryN = new StoreInventory;
                            $storeInventoryN->onHand = doubleval($product['recieve_quantity']);
                            $storeInventoryN->productIncoming = doubleval($product['recieve_quantity']);
                            // $storeInventoryN->safety_stock = doubleval($product['quantity']);
                            $storeInventoryN->mrp = $product['mrp'];
                            $storeInventoryN->price = $product['unitPrice'];
                            $storeInventoryN->productId = $product['productId'];
                            $storeInventoryN->store_id = $storeId;
                            $storeInventoryN->variant_id =  $product['variantId'];
                            $storeInventoryN->variant_name =  $product['variant'];

                            $storeInventoryN->save();

                            $price = new Price;
                            $price->product_id = $product['productId'];
                            $price->price = $product['unitPrice'];
                            $price->mrp = $product['mrp'];
                            $price->quantity = doubleval($product['recieve_quantity']);
                            $price->subscriber_id = Auth::user()->subscriber_id;
                            $price->store_id = $storeId;
                            $price->variant_id = $product['variantId'];
                            $price->variant_name = $product['variant'];
                            $price->product_in_num = 0;

                            $price->save();
                            // $purchase->save();

                        }else{

                            foreach($storeInventories as $storeInventory){
                                $storeInventoryId = $storeInventory->id;
                            }

                            $storeInventoryX = StoreInventory::find($storeInventoryId);

                            $onHand = $storeInventoryX->onHand;
                            $productIncoming = $storeInventoryX->productIncoming;
                            $mrp = $storeInventoryX->mrp;
                            $price = $storeInventoryX->price;

                            $purchaseQuantity = doubleval($product['recieve_quantity']);
                            $purchaseMrp = doubleval($product['mrp']);
                            $purchaseUnitPrice = doubleval($product['unitPrice']);

                            $storeInventoryX->onHand = $onHand + $purchaseQuantity;
                            $storeInventoryX->productIncoming = $productIncoming + $purchaseQuantity;

                            if($mrp != $purchaseMrp || $price != $purchaseUnitPrice){

                                $price = new Price;
                                $price->product_id = $product['productId'];
                                $price->price = $product['unitPrice'];
                                $price->mrp = $product['mrp'];
                                $price->quantity = doubleval($product['recieve_quantity']);
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->store_id = $storeId;
                                $price->variant_id = $product['variantId'];
                                $price->variant_name = $product['variant'];
                                $price->product_in_num = 0;

                                $price->save();

                                $storeInventoryX->mrp = $purchaseMrp;
                                $storeInventoryX->price = $purchaseUnitPrice;
                                $storeInventoryX->save();
                            }

                            $storeInventoryX->save();
                            // $purchase->save();

                        }
                    }
                }
                $purchaseProduct->recieve_quantity = $recieve_quantity;
                $purchaseProduct->save();
            }
        }

        $recieveStatus=0;
        $partialStatus=0;
        $purchaseProducts = PurchaseProductList::where('purchaseProductId', $request->purchaseId)->get();
        foreach($purchaseProducts AS $purchaseProduct)
        {
            if($purchaseProduct['quantity']>$purchaseProduct['recieve_quantity'])
            {
                $partialStatus=1;
                // log::alert($purchaseProduct);
            }
            if($purchaseProduct['quantity']=$purchaseProduct['recieve_quantity'])
            {
                $recieveStatus=1;
                // log::alert($purchaseProduct);
            }
            else
            {
                $recieveStatus=0;
            }
        }

        $purchase = PurchaseProduct::find($request->purchaseId);
        if($recieveStatus==1 && $partialStatus==0){
            $purchase->status = 'received';
            $purchase->save();
            // log::alert($purchase);
        }
        elseif($partialStatus==1){
            $purchase->status = 'partial recieved';
            $purchase->save();
        }
        return response() -> json([
            'status'=>200,
            'message' => 'Partial Received Successfully'
        ]);
    }

    public function productSerial(Request $request){
        // log::info($request);

        if($request->store=='Warehouse'){
            $storeId = 0;
        }
        else{
            $stores = Store::where('store_name', $request->store)->get();
            foreach($stores as $store){
                $storeId = $store->id;
            }
        }

        foreach($request->serialNumbers AS $serialNumber)
        {
            $serial = ProductSerial::where([
                ['productId', $request->productId],
                ['variantId', $request->variantId],
                ['serialNumber', $serialNumber['serialNumber']]
            ])->get();
            foreach($serial as $serialNumber){
                $number = $serialNumber->serialNumber;
            }
            if($serial->isNotEmpty()){
                return response() -> json([
                    'status'=>400,
                    'serialNumber'=>$number,
                    'error' => 'Serial Number Exists'
                ]);
            }
            $productSerial = new ProductSerial;
            $productSerial->productId = doubleval($request->productId);
            $productSerial->productName = $request->productName;
            $productSerial->variantId = doubleval($request->variantId);
            $productSerial->variantName = $request->variant;
            $productSerial->storeId = doubleval($storeId);
            $productSerial->serialNumber = $serialNumber['serialNumber'];
            $productSerial->purchaseId =  doubleval($request->purchaseId);

            $purchaseDateStr                = strtotime($request->purchaseDate);
            $productSerial->purchaseDate  = date('Y-m-d', $purchaseDateStr);

            $productSerial->created_by = Auth::user()->name;
            $productSerial->subscriber_id =  Auth::user()->subscriber_id;

            $productSerial->save();
        }
        return response() -> json([
            'status'=>200,
            'message' => 'Serial Number Added Successfully'
        ]);
    }
}
