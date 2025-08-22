<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Pos;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class PosController extends Controller
{
    public function create(){
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('pos-device/pos-add', ['stores' => $stores]);
    }

    public function store(Request $request){

        $messages = [
            'pos_name.required'  =>    "Name is required.",
            'pospin.required'    =>    "PIN is required.",
            'storeid.required'   =>    "Store is required.",
            'pospin.min'         =>    "Minimum 4 characters.",
        ];

        $validator = Validator::make($request->all(), [
            'pos_name' => 'required',
            'pospin' => 'required|min:4',
            'storeid' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $pos = new Pos;
        
            $pos->pos_name          = $request->pos_name;
            $pos->pos_status        = 'Not Active';
            $pos->pos_pin           = $request->pospin;
            $pos->store_id	        = $request->storeid;
            $pos->subscriber_id     = Auth::user()->subscriber_id;
            $pos->save();

            return response() -> json([
                'status'=>200,
                'message' => 'POS created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

        
    }

    public function listView(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('pos-device/pos-list', ['stores' => $stores]);
    }

    public function list(Request $request){

        $pos = Store::join('p_o_s', 'stores.id', '=', 'p_o_s.store_id')
                ->where('stores.subscriber_id', Auth::user()->subscriber_id)
                ->get(['stores.store_name', 'p_o_s.*']);

        if($request -> ajax()){
            return response()->json([
                'pos'=>$pos,
            ]);
        }
        
    }

    public function edit($id){

        $pos = Pos::find($id);

        if($pos){
            return response()->json([
                'status'=>200,
                'pos'=>$pos,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'pos_name.required'  =>    "Name is required.",
            'pospin.required'    =>    "PIN is required.",
            'storeid.required'   =>    "Store is required.",
            'posstatus.required' =>    "Status is required.",
            'pospin.min'         =>    "Minimum 4 characters.",
        ];

        $validator = Validator::make($request->all(), [
            'pos_name' => 'required',
            'pospin' => 'required|min:4',
            'storeid' => 'required',
            'posstatus' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $pos = Pos::find($id);

            $pos->pos_name    = $request->pos_name;
            $pos->pos_status  = $request->posstatus;
            $pos->store_id    = $request->storeid; 
            $pos->pos_pin     = $request->pospin; 
            
            $pos->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'POS updated successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);
        
    }

    public function destroy($id){
        Pos::find($id)->delete($id);

        return redirect('pos-list')->with('status', 'Deleted successfully!');
    }
}
