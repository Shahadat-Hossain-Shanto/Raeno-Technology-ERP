<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weekend;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WeekendController extends Controller
{
    public function create(){
        return view('weekend/weekend-add');
    }

    public function store(Request $request){

        $messages = [
            'weekendname.required'  =>    "Weekend day is required."
        ];

        $validator = Validator::make($request->all(), [
            'weekendname' => 'required',
           
        ], $messages);

        if ($validator->passes()) {
            $weekend = new Weekend;

            $weekend->weekend_name = $request->weekendname;
            $weekend->subscriber_id = Auth::user()->subscriber_id;
            $weekend->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Weekend created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function listView(){
        return view('weekend/weekend-list');
    }

    public function list(Request $request){

        $weekend = Weekend::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'weekend'=>$weekend,
                'message'=>'Success'
            ]);
        }
    }

    public function destroy($id){
        Weekend::find($id)->delete($id);
        return redirect('weekend-list')->with('status', 'Deleted successfully!');
    }
}
