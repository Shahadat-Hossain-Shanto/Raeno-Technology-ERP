<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Vat;
use App\Models\StoreVat;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class VatController extends Controller
{
    public function create(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('vat/vat-add', ['stores' => $stores]);
    }

    public function store(Request $request){

        $messages = [
            'vatname.required'   =>   "Name is required.",
            'vatrate.required'   =>   "Rate is required.",
            'vattype.required'   =>   "Type is required.",
            'vatoption.required' =>   "Option select one.",
        ];

        $validator = Validator::make($request->all(), [
            'vatname' => 'required',
            'vatrate' => 'required',
            'vattype' => 'required',
            'vatoption' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $vat = new Vat; 

            // $vat->taxId                 = (string)$vat->id;
            $vat->taxName               = $request->vatname;
            $vat->taxAmount             = $request->vatrate;
            $vat->vatType               = $request->vattype;
            $vat->vatOption             = $request->vatoption;
            $vat->subscriber_id         = Auth::user()->subscriber_id;
            
            // $stores             = $request->store;
            // $storeimplode       = implode(', ', $stores);
            // $vat->store         = $storeimplode;

            $vat->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Vat created Successfully!'
            ]);
        }
        

        // $vat->taxId = (string)$vat->id;
        // $vat->save();

        
        // for($i = 0; $i < count($request->store); $i++){

        //     $storeVat = new StoreVat;

        //     $storeVat->taxId          = $vat->id;
        //     $storeVat->taxName        = $request->vatname;
        //     $storeVat->taxAmount      = $request->vatrate;
        //     $storeVat->vatType        = $request->vattype;
        //     $storeVat->vatOption      = $request->vatoption;
        //     $storeVat->storeId        = $request->store[$x];
        //     $storeVat->save();

        // }

        return response()->json(['error'=>$validator->errors()]);
        
    }

    public function listView(){

        $stores = Store::where('subscriber_id',  Auth::user()->subscriber_id)->get();
        $vat = Vat::where('subscriber_id',  Auth::user()->subscriber_id)->get();

        return view('vat/vat-list', ['vats' => $vat, 'stores'=>$stores]);
    }

    public function list(Request $request){

        $stores = Store::where('subscriber_id',  Auth::user()->subscriber_id)->get();
        $vat = Vat::where('subscriber_id',  Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'vat'=>$vat,
            ]);
        }
       
    }

    public function edit($id){
        $vat = Vat::find($id);

        if($vat){
            return response()->json([
                'status'=>200,
                'vat'=>$vat,        
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'vatname.required'   =>   "Name is required.",
            'vatrate.required'   =>   "Rate is required.",
            'vattype.required'   =>   "Type is required.",
            'vatoption.required' =>   "Please select one option."
        ];

        $validator = Validator::make($request->all(), [
            'vatname'   => 'required',
            'vatrate'   => 'required',
            'vattype'   => 'required',
            'vatoption' => 'required'
        ], $messages);

        if ($validator->passes()) {
            $vat = Vat::find($id);

            $vat->taxName       = $request->vatname;
            $vat->taxAmount     = $request->vatrate;
            $vat->vatType       = $request->vattype; 
            $vat->vatOption     = $request->vatoption; 
            
            $vat->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Vat Updated Successfully'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

        
    }

    public function destroy($id){
        Vat::find($id)->delete($id);

        return redirect('vat-list')->with('status', 'Deleted successfully!');
    }
}
