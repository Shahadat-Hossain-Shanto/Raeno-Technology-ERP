<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Subscriber;
use App\Models\LeaveType;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    public function create(){
        return view('leave-type/leave-type-add');
    }

    public function store(Request $request){

        $messages = [
            'leavetype.required'  =>    "Leave type name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'leavetype' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $leaveType = new LeaveType;

            $leaveType->leave_type = $request->leavetype;
            $leaveType->holiday_included = $request->holidayincluded;
            $leaveType->subscriber_id = Auth::user()->subscriber_id;

            $leaveType->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Leave Type created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function listView(){
        return view('leave-type/leave-type-list');
    }

    public function list(Request $request){

        $leaveType = LeaveType::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'leaveType'=>$leaveType,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $leaveType = LeaveType::find($id);

        if($leaveType){
            return response()->json([
                'status'=>200,
                'leaveType'=>$leaveType,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'leavetype.required'  =>    "Leave type name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'leavetype' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $leaveType = LeaveType::find($id);

            $leaveType->leave_type = $request->leavetype;
            $leaveType->holiday_included = $request->holidayincluded;
            
            $leaveType->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Leave Type updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        LeaveType::find($id)->delete($id);
        return redirect('leave-type-list')->with('status', 'Deleted successfully!');
    }
}
