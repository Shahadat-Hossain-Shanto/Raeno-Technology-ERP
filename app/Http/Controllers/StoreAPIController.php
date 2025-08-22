<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class StoreAPIController extends Controller
{
    public function store(Request $request)
    {

        $messages = [
            'store_name.required'    =>    "Name is required.",
            'storeaddress.required'  =>    "Address is required.",
            'contactnumber.required' =>    "Contact Number is required.",
            'store_name.unique'    =>      "Store name has already been taken.",
        ];

        $validator = Validator::make($request->all(), [
            'store_name' => 'required',
            'storeaddress' => 'required',
            'contactnumber' => 'required',
        ], $messages);

        if ($validator->passes()) {

            $store = new Store;

            $store->subscriber_id                 = Auth::user()->subscriber_id;
            $store->store_name                    = $request->store_name;
            $store->store_address                 = $request->storeaddress;
            $store->contact_number                = $request->contactnumber;

            $store->save();

            return response()->json([
                'status' => 200,
                'message' => 'Store created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);


    }

    public function list($subscriberId)
    {
        $store = Store::select('id','store_name')->where('subscriber_id', $subscriberId)->get();

        return response()->json([
            'stores' => $store,
        ]);
    }

    public function edit($id)
    {
        $store = Store::find($id);

        if ($store) {
            return response()->json([
                'status' => 200,
                'store' => $store,

            ]);
        }
    }

    public function update(Request $request, $id)
    {

        $messages = [
            'store_name.required'    =>    "Name is required.",
            'storeaddress.required'  =>    "Address is required.",
            'contactnumber.required' =>    "Contact Number is required.",
            'store_name.unique'    =>      "Store name has already been taken."
        ];

        $validator = Validator::make($request->all(), [
            'store_name' => "required|unique:stores,id, $id",
            'storeaddress' => 'required',
            'contactnumber' => 'required',
        ], $messages);


        if ($validator->passes()) {
            $store = Store::find($id);

            $store->store_name    = $request->store_name;
            $store->store_address  = $request->storeaddress;
            $store->contact_number = $request->contactnumber;

            $store->save();

            return response()->json([
                'status' => 200,
                'message' => 'Store updated successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function destroy($id)
    {
        Store::find($id)->delete($id);

        return redirect('warehouse-list')->with('status', 'Deleted successfully!');
    }
}
