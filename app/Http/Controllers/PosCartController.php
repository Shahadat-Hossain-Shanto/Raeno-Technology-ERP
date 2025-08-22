<?php

namespace App\Http\Controllers;

use DB;

use Log;
use Session;
use Redirect;
use App\Models\Pos;
use App\Models\Vat;
use App\Models\Bank;
use App\Models\Store;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;

use App\Models\Discount;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

class PosCartController extends Controller
{
    public function index(Request $request){

        if($request->session()->has('storeId') && $request->session()->has('posId')){

            $clients = Client::where('subscriber_id', Auth::user()->subscriber_id)->get();
            $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
            $methods = PaymentMethod::where('subscriber_id', Auth::user()->subscriber_id)->get();
            $storeId = $request->session()->get('storeId');
            $banks = Bank::where('subscriber_id', Auth::user()->subscriber_id)->get();
            $posId = $request->session()->get('posId');
            $posName = $request->session()->get('posName');

            $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('categories', 'categories.id', 'products.category')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $storeId]
                        ])
                    ->groupBy("store_inventories.productId", "products.productName", "products.productImage", "products.id", "products.brand", "categories.category_image")
                    // ->take(1)
                    ->simplePaginate(12);

            // Log::info($products->links());

            return view('pos/pos', ['customers' => $clients, 'stores' => $stores, 'banks' => $banks,"storeId" => $storeId, "posId" => $posId, "posName" => $posName, "methods" => $methods, "products" => $products]);
        }else{
            return redirect('pos-login');
        }

    }

    public function search(Request $request, $keyword){

        $sessionStoreId = $request->session()->get('storeId');
        $products = DB::table("products")
                    ->join('categories', 'categories.id', 'products.category')
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->join('product_serials', 'store_inventories.productId', '=', 'product_serials.productId')
                    ->select('product_serials.serialNumber', "store_inventories.onHand","products.id","products.productName","products.type",'store_inventories.variant_name', 'store_inventories.variant_id',"variants.variant_image", "categories.category_image")
                    // ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                            ['products.productName','LIKE','%'.$keyword."%"],
                            ['store_inventories.onHand', '>', 0]
                        ])
                    ->orWhere([
                        ['products.subscriber_id', Auth::user()->subscriber_id],
                        ['store_inventories.store_id', $sessionStoreId],
                        ['products.barcode', $keyword],
                        ['store_inventories.onHand', '>', 0]
                    ])
                    ->orWhere([
                        ['products.subscriber_id', Auth::user()->subscriber_id],
                        ['store_inventories.store_id', $sessionStoreId],
                        ['product_serials.serialNumber', $keyword],
                        ['store_inventories.onHand', '>', 0]
                    ])
                    ->orWhere([
                        ['products.subscriber_id', Auth::user()->subscriber_id],
                        ['store_inventories.store_id', $sessionStoreId],
                        ['variants.variant_name','LIKE','%'.$keyword."%"],
                        ['store_inventories.onHand', '>', 0]
                    ])
                    ->groupBy("store_inventories.productId", "products.productName", "products.productImage", "products.id", "store_inventories.variant_name","products.brand", "variants.variant_image")
                    // ->take(Auth::user()->subscriber_id)
                    ->simplePaginate(12);



        if($request -> ajax()){
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'products'=>$products,
            ]);
        }

    }

    // public function fetchSearch(Request $request, $keyword){
    //     $sessionStoreId = $request->session()->get('storeId');
    //     $products = DB::table("products")
    //                 ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
    //                 ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
    //                 ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description")
    //                 ->where([
    //                         ['products.subscriber_id', Auth::user()->subscriber_id],
    //                         ['store_inventories.store_id', $sessionStoreId],
    //                         ['products.productName','LIKE','%'.$keyword."%"]
    //                     ])
    //                 ->orWhere([
    //                     ['products.subscriber_id', Auth::user()->subscriber_id],
    //                     ['store_inventories.store_id', $sessionStoreId],
    //                     ['products.barcode', $keyword]
    //                 ])
    //                 ->orWhere([
    //                     ['products.subscriber_id', Auth::user()->subscriber_id],
    //                     ['store_inventories.store_id', $sessionStoreId],
    //                     ['variants.variant_name','LIKE','%'.$keyword."%"]
    //                 ])
    //                 ->get();



    //     if($request -> ajax()){
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'success',
    //             'products'=>$products,
    //         ]);
    //     }
    // }

    public function fetch(Request $request){

        $sessionStoreId = $request->session()->get('storeId');

        if($request->ajax()){
            $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('categories', 'categories.id', 'products.category')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId]
                        ])
                    ->groupBy("store_inventories.productId", "store_inventories.variant_name","products.productName", "products.productImage", "products.id", "products.brand", "categories.category_image")
                    // ->take(1)
                    ->simplePaginate(12);

            // return view('pagination_child', compact('data'))->render();
            return response()->json([
                'message' => 'Success',
                'products' => $products,
            ]);
        }
    }

    public function fetchSearch(Request $request, $keyword){
        $sessionStoreId = $request->session()->get('storeId');
        $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('categories', 'categories.id', 'products.category')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                            ['products.productName','LIKE','%'.$keyword."%"]
                        ])
                    ->orWhere([
                        ['products.subscriber_id', Auth::user()->subscriber_id],
                        ['store_inventories.store_id', $sessionStoreId],
                        ['products.barcode', $keyword]
                    ])
                    ->orWhere([
                        ['products.subscriber_id', Auth::user()->subscriber_id],
                        ['store_inventories.store_id', $sessionStoreId],
                        ['variants.variant_name','LIKE','%'.$keyword."%"]
                    ])
                    ->groupBy("store_inventories.productId", "store_inventories.variant_name", "products.productName", "products.productImage", "products.id", "products.brand", "categories.category_image")
                    // ->take(Auth::user()->subscriber_id)
                    ->simplePaginate(12);



        if($request -> ajax()){
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'products'=>$products,
            ]);
        }
    }

     public function fetchCategorySearch(Request $request, $id){
        $sessionStoreId = $request->session()->get('storeId');

        if($id == "all_products"){
            $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('categories', 'categories.id', 'products.category')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                        ])
                    ->groupBy("store_inventories.productId", "store_inventories.variant_name","products.productName", "products.productImage", "products.id", "products.brand", "categories.category_image")
                    // ->take(1)
                    ->simplePaginate(12);
        }else{
            $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('categories', 'categories.id', 'products.category')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                            ['products.category', $id]
                        ])
                    ->groupBy("store_inventories.productId", "store_inventories.variant_name","products.productName", "products.productImage", "products.id", "products.brand", "categories.category_image")
                    // ->take(1)
                    ->simplePaginate(12);
        }

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'products'=>$products,
            ]);
        }
     }
    public function categories(Request $request){


        //Log::info("s");

        $sessionStoreId = $request->session()->get('storeId');

        $categories = Category::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->join('categories', 'categories.id', 'products.category')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                        ])
                        ->groupBy("store_inventories.productId", "products.productName", "products.productImage", "products.id", "store_inventories.variant_name","products.brand", "categories.category_image")
                    ->simplePaginate(12);

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'categories' => $categories,
                'products' => $products,
            ]);
        }
    }

    public function searchCategories(Request $request, $id){

        $sessionStoreId = $request->session()->get('storeId');

        if($id == "all_products"){
            $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->join('categories', 'categories.id', 'products.category')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                        ])
                    ->groupBy("store_inventories.productId", "products.productName", "products.productImage", "products.id", "store_inventories.variant_name","products.brand", "categories.category_image")
                    ->simplePaginate(12);
        }else{
            $products = DB::table("products")
                    ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                    ->join('variants', 'store_inventories.variant_id', '=', 'variants.id')
                    ->join('categories', 'categories.id', 'products.category')
                    ->select("store_inventories.onHand", "products.productName", "products.productImage", "products.id", "store_inventories.productId", "store_inventories.variant_name", "store_inventories.variant_id", "variants.variant_image", "variants.variant_description", "categories.category_image")
                    ->where([
                            ['products.subscriber_id', Auth::user()->subscriber_id],
                            ['store_inventories.store_id', $sessionStoreId],
                            ['products.category', $id]
                        ])
                        ->groupBy("store_inventories.productId", "products.productName", "products.productImage", "products.id", "store_inventories.variant_name","products.brand", "categories.category_image")
                        ->simplePaginate(12);
        }



        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'products'=>$products,
            ]);
        }
    }

    public function productAdd(Request $request, $id, $variantId){

        $sessionStoreId = $request->session()->get('storeId');

        $products = DB::table("products")
                ->where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['products.id', $id]
                ])
                ->get();

        $data = DB::table("prices")
                ->join('variants', 'prices.variant_id', '=', 'variants.id')
                ->select("prices.mrp", "prices.variant_name", "prices.variant_id","variants.*")
                ->where([
                    ['prices.subscriber_id', Auth::user()->subscriber_id],
                    ['prices.store_id', $sessionStoreId],
                    ['variants.id', $variantId]
                ])
                ->orderBy('prices.id', 'desc')
                ->take(1)
                ->get();

        foreach($products as $p){
            foreach($data as $d){
                $x =[
                    "id" => $p->id,
                    "productName" => $p->productName,
                    "productLabel" => $p->productLabel,
                    "brand" => $p->brand,
                    "category" => $p->category,
                    "category_name" => $p->category_name,
                    "subcategory" => $p->subcategory,
                    "subcategory_name" => $p->subcategory_name,
                    "type" => $p->type,
                    "sku" => $p->sku,
                    "barcode" => $p->barcode,
                    "supplier" => $p->supplier,
                    "available_discount" => $d->available_discount,
                    "discount" => $d->discount,
                    "discount_type" => $d->discount_type,
                    "available_offer" => $p->available_offer,
                    "offerItemId" => $p->offerItemId,
                    "freeItemName" => $p->freeItemName,
                    "requiredQuantity" => $p->requiredQuantity,
                    "freeQuantity" => $p->freeQuantity,
                    "isExcludedTax" => $d->isExcludedTax,
                    "taxName" => $d->taxName,
                    "tax" => $d->tax,
                    "created_by" => $p->created_by,
                    "updated_by" => $p->updated_by,
                    "created_at" => $p->created_at,
                    "updated_at" => $p->updated_at,
                    "subscriber_id" => $p->subscriber_id,
                    "productImage" => $p->productImage,
                    "mrp" => $d->mrp,
                    "variant_name" => $d->variant_name,
                    "variant_id" => $d->variant_id

                ];
            }
        }

        $y[] = $x;



        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'products'=>$y


            ]);
        }
    }

    public function customerSearch(Request $request, $id){
        $client = Client::where('id', $id)->get();

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'customer'=>$client,
            ]);
        }
    }

    public function discountSearch(Request $request, $id){
        $discount = Discount::where('discount', $id)->get();

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'discount'=>$discount,
            ]);
        }
    }

    public function taxSearch(Request $request, $id){
        $tax = Vat::where('taxAmount', $id)->get();

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'tax'=>$tax,
            ]);
        }
    }

    public function posLoginView(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $userStoreId = Auth::user()->store_id;
        // log::info($userStoreId);
       return view('pos/pos-login', ['stores' => $stores, 'userStoreId' => $userStoreId]);
    }

    public function posSearch(Request $request, $id){
        $poses = Pos::where('store_id', $id)->get();

        if($request -> ajax()){
            return response()->json([
                'message' => 'Success',
                'poses'=>$poses,
            ]);
        }
    }

    public function posLogin(Request $request){

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

            return response()->json(['url'=>url('/pos'), 'message' => 'Success!']);

        }else{

            return response()->json(['message' => 'POS PIN not matched!']);

        }
    }

    public function posLogout(Request $request){

        $request->session()->forget('storeId');
        $request->session()->forget('storeName');
        $request->session()->forget('storeAddress');
        $request->session()->forget('posId');
        $request->session()->forget('posName');
        $request->session()->forget('orgName');
        $request->session()->forget('binNumber');

        return response()->json([
            'url'=>url('/pos-login')
        ]);

    }



    public function productAdd2(Request $request, $id, $variantId, $storeId){

        // $sessionStoreId = $request->session()->get('storeId');

        if($storeId=='inventory'||$storeId=='0')
        {
            $products = DB::table("products")
                ->where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['products.id', $id]
                ])
                ->get();

            $data = DB::table("prices")
                    ->join('variants', 'prices.variant_id', '=', 'variants.id')
                    ->select("prices.mrp", "prices.variant_name", "prices.variant_id", 'variants.*')
                    ->where([
                        ['prices.subscriber_id', Auth::user()->subscriber_id],
                        ['prices.store_id', $storeId],
                        ['variants.id', $variantId]
                    ])
                    ->orderBy('prices.id', 'desc')
                    ->take(1)
                    ->get();

            $onHand = DB::table("products")
                ->join('inventories', 'products.id', '=', 'inventories.productId')
                ->select("onHand")
                ->where([
                    ['inventories.productId', $id],
                    ['inventories.variant_id', $variantId]

                ])
                ->first();

            // Log::info($onHand);

            foreach($products as $p){
                foreach($data as $d){
                    $x =[
                        "id" => $p->id,
                        "productName" => $p->productName,
                        "productLabel" => $p->productLabel,
                        "brand" => $p->brand,
                        "category" => $p->category,
                        "category_name" => $p->category_name,
                        "subcategory" => $p->subcategory,
                        "subcategory_name" => $p->subcategory_name,
                        "sku" => $p->sku,
                        "barcode" => $p->barcode,
                        "supplier" => $p->supplier,
                        "available_discount" => $d->available_discount,
                        "discount" => $d->discount,
                        "discount_type" => $d->discount_type,
                        "available_offer" => $p->available_offer,
                        "offerItemId" => $p->offerItemId,
                        "freeItemName" => $p->freeItemName,
                        "requiredQuantity" => $p->requiredQuantity,
                        "freeQuantity" => $p->freeQuantity,
                        "isExcludedTax" => $d->isExcludedTax,
                        "taxName" => $d->taxName,
                        "tax" => $d->tax,
                        "created_by" => $p->created_by,
                        "updated_by" => $p->updated_by,
                        "created_at" => $p->created_at,
                        "updated_at" => $p->updated_at,
                        "subscriber_id" => $p->subscriber_id,
                        "productImage" => $p->productImage,
                        "mrp" => $d->mrp,
                        "variant_name" => $d->variant_name,
                        "variant_id" => $d->variant_id

                    ];
                }
            }

            $y[] = $x;



            if($request -> ajax()){
                return response()->json([
                    'message' => 'Success',
                    'products'=>$y,
                    'onHand' => $onHand->onHand


                ]);
            }
        }
        else
        {
            $products = DB::table("products")
                ->where([
                    ['products.subscriber_id', Auth::user()->subscriber_id],
                    ['products.id', $id]
                ])
                ->get();

            $data = DB::table("prices")
                    ->join('variants', 'prices.variant_id', '=', 'variants.id')
                    ->select("prices.mrp", "prices.variant_name", "prices.variant_id", 'variants.*')
                    ->where([
                        ['prices.subscriber_id', Auth::user()->subscriber_id],
                        ['prices.store_id', $storeId],
                        ['variants.id', $variantId]
                    ])
                    ->orderBy('prices.id', 'desc')
                    ->take(1)
                    ->get();

            $onHand = DB::table("products")
                ->join('store_inventories', 'products.id', '=', 'store_inventories.productId')
                ->select("onHand")
                ->where([
                    ['store_inventories.productId', $id],
                    ['store_inventories.variant_id', $variantId],
                    ['store_inventories.store_id', $storeId]

                ])
                ->first();

            // Log::info($onHand);

            foreach($products as $p){
                foreach($data as $d){
                    $x =[
                        "id" => $p->id,
                        "productName" => $p->productName,
                        "productLabel" => $p->productLabel,
                        "brand" => $p->brand,
                        "category" => $p->category,
                        "category_name" => $p->category_name,
                        "subcategory" => $p->subcategory,
                        "subcategory_name" => $p->subcategory_name,
                        "sku" => $p->sku,
                        "barcode" => $p->barcode,
                        "supplier" => $p->supplier,
                        "available_discount" => $d->available_discount,
                        "discount" => $d->discount,
                        "discount_type" => $d->discount_type,
                        "available_offer" => $p->available_offer,
                        "offerItemId" => $p->offerItemId,
                        "freeItemName" => $p->freeItemName,
                        "requiredQuantity" => $p->requiredQuantity,
                        "freeQuantity" => $p->freeQuantity,
                        "isExcludedTax" => $d->isExcludedTax,
                        "taxName" => $d->taxName,
                        "tax" => $d->tax,
                        "created_by" => $p->created_by,
                        "updated_by" => $p->updated_by,
                        "created_at" => $p->created_at,
                        "updated_at" => $p->updated_at,
                        "subscriber_id" => $p->subscriber_id,
                        "productImage" => $p->productImage,
                        "mrp" => $d->mrp,
                        "variant_name" => $d->variant_name,
                        "variant_id" => $d->variant_id

                    ];
                }
            }

            $y[] = $x;



            if($request -> ajax()){
                return response()->json([
                    'message' => 'Success',
                    'products'=>$y,
                    'onHand' => $onHand->onHand


                ]);
            }
        }
    }
}
