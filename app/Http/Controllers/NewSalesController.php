<?php

namespace App\Http\Controllers;

use Log;
use Session;
use App\Models\Pos;
use App\Models\Bank;
use App\Models\User;
use App\Models\Batch;
use App\Models\Price;
use App\Models\Store;
use App\Models\Client;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

use App\Models\ProductSerial;
use App\Models\StoreInventory;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseProductList;
use Illuminate\Support\Facades\Auth;

class NewSalesController extends Controller
{
    public function newSalesLoginView(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $userStoreId = Auth::user()->store_id;
        // log::info($userStoreId);
       return view('new-sales/new-sales-login', ['stores' => $stores, 'userStoreId' => $userStoreId]);
    }
    public function newSalesLogin(Request $request){

        $posId = (int)$request->posId;
        $subscriberId = Auth::user()->subscriber_id;

        $subscriber = Subscriber::find($subscriberId);
        $orgName = $subscriber->org_name;
        $binNumber = $subscriber->bin_number;

        $store = Store::find((int)$request->storeId);
        $storeAddress = $store->store_address;


        $pos = Pos::find($posId);

        $clients = Client::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($pos->pos_pin == $request->posPin){
            Session::put('storeId', (int)$request->storeId);
            Session::put('storeName', $request->storeName);
            Session::put('storeAddress', $storeAddress);
            Session::put('posId', (int)$request->posId);
            Session::put('posName', $pos->pos_name);
            Session::put('orgName', $orgName);
            Session::put('binNumber', $binNumber);

            return response()->json(['url'=>url('/new-sales-create'), 'message' => 'Success!']);

        }else{

            return response()->json(['message' => 'POS PIN not matched!']);

        }
    }

    public function newSalesLogout(Request $request){

        $request->session()->forget('storeId');
        $request->session()->forget('storeName');
        $request->session()->forget('storeAddress');
        $request->session()->forget('posId');
        $request->session()->forget('posName');
        $request->session()->forget('orgName');
        $request->session()->forget('binNumber');

        return response()->json([
            'url'=>url('/new-sales-login')
        ]);

    }

    public function create(Request $request){
        // if($request->session()->has('storeId') && $request->session()->has('posId')){
        $products = Product::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $clients = Client::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $batches = Batch::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $methods = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $banks = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where([
            ['subscriber_id', Auth::user()->subscriber_id],
        ])->get();


        // return $products;
        return view('new-sales/new-sales', ['products' => $products, 'clients' => $clients, 'stores' => $stores, 'batches' => $batches,'variants' => $variants,'methods' =>$methods, 'banks' => $banks]);
        // }else{
        //     return redirect('new-sales-login');
        // }
    }

    public function getstore($id)
    {
        $store = Store::where('id', $id)->get();
        return response()->json([
            'store' => $store
        ]);
    }

    public function search($storeId, $keyword){

        if($storeId=='inventory')
        {
            $products = DB::table("products")
                ->join('product_serials', 'product_serials.productId', 'products.id')
                ->join('inventories', 'products.id', '=', 'inventories.productId')
                ->join('variants', 'inventories.variant_id', '=', 'variants.id')
                ->select("inventories.onHand", "inventories.mrp", "products.id","products.productName","products.type",'inventories.variant_name', 'inventories.variant_id', 'variants.isExcludedTax', 'variants.tax', 'variants.discount_type', 'variants.discount', 'product_serials.serialNumber', 'product_serials.saleId')
                ->Where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['product_serials.storeId', 0],
                    ['product_serials.serialNumber', $keyword],
                    ['inventories.onHand', '>', 0]
                ])
                ->orWhere([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['product_serials.storeId', 0],
                    ['products.barcode', $keyword],
                    ['inventories.onHand', '>', 0]
                ])
                ->groupBy("inventories.productId", "products.productName", "products.productImage", "products.id", "inventories.variant_name","products.brand", "variants.variant_image")
                // ->take(Auth::user()->subscriber_id)
                ->get();// ->simplePaginate(12);
        }
        else{
            $products = DB::table("products")
                ->join('product_serials', 'product_serials.productId', 'products.id')
                ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                ->select("store_inventories.onHand", "store_inventories.mrp", "products.id","products.productName","products.type",'store_inventories.variant_name', 'store_inventories.variant_id', 'variants.isExcludedTax', 'variants.tax', 'variants.discount_type', 'variants.discount', 'product_serials.serialNumber', 'product_serials.saleId')
                // ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                ->Where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['store_inventories.store_id', $storeId],
                    ['product_serials.storeId', $storeId],
                    ['product_serials.serialNumber', $keyword],
                    ['store_inventories.onHand', '>', 0]
                ])
                ->orWhere([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['store_inventories.store_id', $storeId],
                    ['product_serials.storeId', $storeId],
                    ['products.barcode', $keyword],
                    ['store_inventories.onHand', '>', 0]
                ])
                ->groupBy("store_inventories.productId", "products.productName", "products.productImage", "products.id", "store_inventories.variant_name","products.brand", "variants.variant_image")
                // ->take(Auth::user()->subscriber_id)
                ->get();// ->simplePaginate(12);
        }



            return response()->json([
                'status' => 200,
                'message' => 'success',
                'products'=>$products,
            ]);

    }

    public function productSerial($productId, $variantId, $storeId, $serial){

        $serial = ProductSerial::where([
                ['productId', $productId],
                ['variantId', $variantId],
                ['storeId', $storeId],
                ['serialNumber', $serial],
                ['saleId', 0]
            ])->get();

            if($serial->isNotEmpty()){
                return response() -> json([
                    'status'=>200,
                    'serialNumber'=>$serial[0]->serialNumber,
                    'message' => 'Serial Number OK'
                ]);
            }

        return response() -> json([
            'status'=>400,
            'message' => 'Serial Number Invalid or Sold'
        ]);
    }
}
