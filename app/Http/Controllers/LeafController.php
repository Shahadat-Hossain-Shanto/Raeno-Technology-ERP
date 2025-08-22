<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaf;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LeafController extends Controller
{
    public function create(){
        return view('leaf-setting/leaf-add');
    }

    public function store(Request $request){

        $messages = [
            'leaftype.required'  =>    "Leaf type is required.",
            'totalnumberofperbox.required'  =>    "Total number of per box is required.",
        ];

        $validator = Validator::make($request->all(), [
            'leaftype' => 'required',
            'totalnumberofperbox' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $leaf = new Leaf;

            $leaf->leaf_type                = $request->leaftype;
            $leaf->total_number_of_per_box  = $request->totalnumberofperbox;
            $leaf->subscriber_id            = Auth::user()->subscriber_id;
            $leaf->user_id                  = Auth::user()->id;

            $leaf->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Leaf created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        

        
    }

    public function listView(){
        return view('leaf-setting/leaf-list');
    }

    public function list(Request $request){

        $leaf = Leaf::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'leaf'=>$leaf,
            ]);
        }
    }

    public function edit($id){
        $leaf = Leaf::find($id);

        if($leaf){
            return response()->json([
                'status'=>200,
                'leaf'=>$leaf,
                
            ]);
        }
    }

    public function update(Request $request, $id){
        $messages = [
            'leaftype.required'  =>    "Leaf type is required.",
            'totalnumberofperbox.required'  =>    "Total number of per box is required.",
        ];

        $validator = Validator::make($request->all(), [
            'leaftype' => 'required',
            'totalnumberofperbox' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $leaf = Leaf::find($id);

            $leaf->leaf_type                    = $request->leaftype;
            $leaf->total_number_of_per_box      = $request->totalnumberofperbox;
            
            $leaf->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Leaf ipdated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        Leaf::find($id)->delete($id);
        return redirect('leaf-list')->with('status', 'Deleted successfully!');
    }
}
