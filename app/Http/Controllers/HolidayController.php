<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function create(){
        return view('holiday/holiday-add');
    }

    public function store(Request $request){

        $messages = [
            'holidayname.required'  =>    "Holiday name is required.",
            'startdate.required'  =>    "Start date is required.",
            'enddate.required'  =>    "End date is required.",
        ];

        $validator = Validator::make($request->all(), [
            'holidayname' => 'required',
            'startdate' => 'required',
            'enddate'  => 'required'
        ], $messages);

        if ($validator->passes()) {
            $holiday = new Holiday;

            $holiday->holiday_name = $request->holidayname;
            $holiday->start_date = $request->startdate;
            $holiday->end_date = $request->enddate;
            $holiday->subscriber_id = Auth::user()->subscriber_id;
            $holiday->user_id = Auth::user()->id;

            $holiday->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Holiday created Successfully!'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function listView(){
        return view('holiday/holiday-list');
    }

    public function list(Request $request){

        $holiday = Holiday::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'holiday'=>$holiday,
                'message'=>'Success'
            ]);
        }
    }

    public function edit($id){
        $holiday = Holiday::find($id);

        if($holiday){
            return response()->json([
                'status'=>200,
                'holiday'=>$holiday,
                
            ]);
        }
    }

    public function update(Request $request, $id){

        $messages = [
            'holidayname.required'  =>    "Holiday name is required.",
            'startdate.required'  =>    "In time is required.",
            'enddate.required'  =>    "Out time is required.",
        ];

        $validator = Validator::make($request->all(), [
            'holidayname' => 'required',
            'startdate' => 'required',
            'enddate'  => 'required'
        ], $messages);

        if ($validator->passes()) {
            $holiday = Holiday::find($id);

            $holiday->holiday_name = $request->holidayname;
            $holiday->start_date = $request->startdate;
            $holiday->end_date = $request->enddate;
            
            $holiday->save();
            
            return response() -> json([
                'status'=>200,
                'message' => 'Holiday updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);        
        
    }

    public function destroy($id){
        Holiday::find($id)->delete($id);
        return redirect('holiday-list')->with('status', 'Deleted successfully!');
    }
}
