<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductUnit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductUnitController extends Controller
{
    public function create(){
        return view('product/product-unit-add');
    }

    public function store(Request $request){
        $messages = [
            'unitname.required'  =>    "Unit name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'unitname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $unit = new ProductUnit;

            $unit->name = $request->unitname;
            $unit->description = $request->description;
            $unit->subscriber_id = Auth::user()->subscriber_id;
            $unit->user_id = Auth::user()->id;

            $unit->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Unit created successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    public function listView(){
        return view('product/product-unit-list');
    }

    public function list(Request $request){

        $unit = ProductUnit::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'unit'=>$unit,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $unit = ProductUnit::find($id);

        if($unit){
            return response()->json([
                'status'=>200,
                'unit'=>$unit,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'unitname.required'  =>    "Unit name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'unitname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $unit = ProductUnit::find($id);

            $unit->name         = $request->unitname;
            $unit->description  = $request->description;
            
            $unit->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Batch updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        ProductUnit::find($id)->delete($id);
        return redirect('product-unit-list')->with('status', 'Deleted successfully!');
    }
}
