<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Store;
use App\Models\Discount;
use Illuminate\Http\Request;



use App\Models\StoreDiscount;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;



class DiscountController extends Controller
{
    public function create(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('discount/discount-add', ['stores' => $stores]);
    }

    public function store(Request $request){

        $messages = [
            'discountname.required'  =>    "Name is required.",
            'discounttype.required'  =>    "Type is required.",
            'value.required'         =>    "Value is required.",
            // 'store.required'         =>    "Store is required.",
            'storex.required'         =>   "Please select one.",
        ];

        $validator = Validator::make($request->all(), [
            'discountname' => 'required',
            'discounttype' => 'required',
            'value' => 'required',
            // 'store' => 'required',
            'storex' => 'required',
        ], $messages);

        if ($validator->passes()) {


            $discount = new Discount;


            $discount->discountName     = $request->discountname;
            $discount->discountType     = $request->discounttype;
            $discount->discount         = $request->value;
            // $discount->	isRestricted    = $request->isrestricted;
            $discount->subscriber_id    = Auth::user()->subscriber_id;


            if($request->storex == "all_store"){
                $discount->store            = $request->storex;
            }else{
                $stores                     = $request->store;
                $storeimplode               = implode(', ', $stores);
                $discount->store            = $storeimplode;
            }
            $discount->save();

            if($request->storex == "all_store"){
                $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

                foreach($stores as $store){
                    $storeDiscount = new StoreDiscount;

                    $storeDiscount->discountName        = $request->discountname;
                    $storeDiscount->discountType        = $request->discounttype;
                    $storeDiscount->discount            = $request->value;
                    // $storeDiscount->isRestricted        = $request->isrestricted;
                    $storeDiscount->storeId             = $store->id;
                    $storeDiscount->discountId          = $discount->id;
                    $storeDiscount->subscriber_id       = Auth::user()->subscriber_id;

                    $storeDiscount->save();
                }
            }else{
                for($i = 0; $i < count($request->store); $i++){
                    $storeDiscount = new StoreDiscount;

                    $storeDiscount->discountName        = $request->discountname;
                    $storeDiscount->discountType        = $request->discounttype;
                    $storeDiscount->discount            = $request->value;
                    // $storeDiscount->isRestricted        = $request->isrestricted;
                    $storeDiscount->storeId             = $request->store[$i];
                    $storeDiscount->discountId          = $discount->id;
                    $storeDiscount->subscriber_id       = Auth::user()->subscriber_id;

                    $storeDiscount->save();


                }
            }

                return response() -> json([
                    'status'=>200,
                    'message' => 'Discount created Successfully!'
                ]);
            }

        return response()->json(['error'=>$validator->errors()]);
    }

    public function listView(){
        $discount = Discount::where('subscriber_id',  Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('discount/discount-list', ['discounts' => $discount, 'stores'=>$stores]);
    }

    public function list(Request $request){

        $discount = StoreDiscount::join('stores', 'store_discounts.storeId', 'stores.id')
                        ->where('store_discounts.subscriber_id',  Auth::user()->subscriber_id)
                        ->get(['store_discounts.*', 'stores.store_name']);

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        if($request -> ajax()){
            return response()->json([
                'discount'=>$discount,
            ]);
        }
    }

    public function edit($id){
        $discount = StoreDiscount::find($id);

        if($discount){
            return response()->json([
                'status'=>200,
                'discount'=>$discount,
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'discountname.required'  =>    "Name is required.",
            'discounttype.required'  =>    "Type is required.",
            'value.required'         =>    "Value is required.",
            'store.required'         =>    "Store is required.",
        ];

        $validator = Validator::make($request->all(), [
            'discountname' => 'required',
            'discounttype' => 'required',
            'value' => 'required',
            'store' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $discount = StoreDiscount::find($id);

            $discount->discountName         = $request->discountname;
            $discount->discountType         = $request->discounttype;
            $discount->discount                = $request->value;
            // $discount->isRestricted         = $request->isrestricted;

            $stores                     = $request->store;
            $storeimplode               = implode(', ', $stores);
            $discount->storeId            = $storeimplode;

            $discount->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Discount Updated Successfully'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);


    }

    public function destroy($id){
        StoreDiscount::find($id)->delete($id);

        return redirect('discount-list')->with('status', 'Deleted successfully!');
    }

    public function listJson(){

        $data = Discount::find(1);

        return response() -> json([
            "status" => "success",
            "statusCode" => 200,
            "message" => "Data found",
            "role" => null,
            "data" => $data,

        ]);
    }

}
