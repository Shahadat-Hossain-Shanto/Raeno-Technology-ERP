<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class BatchController extends Controller
{

    public function create(){
        return view('batch/batch-add');
    }

    public function store(Request $request){

        $messages = [
            'batchnumber.required'  =>    "Batch number is required.",
            'expirydate.required'  =>    "Expiry date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'batchnumber' => 'required',
            'expirydate' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $batch = new Batch;

            $batch->batch_number = $request->batchnumber;
            $batch->expiry_date = $request->expirydate;
            $batch->subscriber_id = Auth::user()->subscriber_id;
            $batch->user_id = Auth::user()->id;

            $batch->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Batch created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function listView(){
        return view('batch/batch-list');
    }

    public function list(Request $request){

        $batch = Batch::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'batch'=>$batch,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $batch = Batch::find($id);

        if($batch){
            return response()->json([
                'status'=>200,
                'batch'=>$batch,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'batchnumber.required'  =>    "Batch number is required.",
            'expirydate.required'  =>    "Expiry date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'batchnumber' => 'required',
            'expirydate' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $batch = Batch::find($id);

            $batch->batch_number    = $request->batchnumber;
            $batch->expiry_date     = $request->expirydate;
            
            $batch->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Batch updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        Batch::find($id)->delete($id);
        return redirect('batch-list')->with('status', 'Deleted successfully!');
    }
}
