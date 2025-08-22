<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{

    public function index()
    {

        return view('designation.designation-list');
    }

    public function indexData(Request $req)
    {
        $designation = Designation::where('subscriber_id',Auth::user()->subscriber_id)->get();
        if ($req->ajax()) {
            return response()->json([
                'designation' => $designation,
            ]);
        }
    }

    public function create()
    {
        return view('designation.designation-add');
    }

    public function store(Request $req)
    {
        $messages = [
            'designation_name.required'  =>    "Designation name is required.",
            // 'designation_description.required'  =>    "Designation description is required.",
        ];
        $validator = Validator::make($req->all(), [
            'designation_name' => 'required',
            // 'designation_description' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $designation = new Designation;
            $designation->designation_name = $req->designation_name;
            $designation->designation_description = $req->designation_description;

            $designation->subscriber_id = Auth::user()->subscriber_id;

            $designation->save();
            return redirect()->route('designation.list.view');
        }
        return response()->json(['error' => $validator->errors()]);
        // return json_encode(['error' => $validator->errors()]);
    }

    public function edit(Request $req, $id)
    {
        $designation = Designation::find($id);
        if ($req->ajax()) {
            return response()->json([
                'status' => 200,
                'designation' => $designation,
            ]);
        }
    }

    public function update(Request $req, $id)
    {
        $messages = [
            'designation_name.required'  =>    "Designation Name is required.",
            // 'designation_description.required'  =>    "Designation description is required.",
        ];
        $validator = Validator::make($req->all(), [
            'designation_name' => 'required',
            // 'designation_description' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $designation = Designation::find($id);
            $designation->designation_name = $req->designation_name;
            $designation->designation_description = $req->designation_description;
            $designation->save();

            return response()->json([
                'status' => 200,
                'message' => 'Updated successfully'
            ]);
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        Designation::find($id)->delete($id);

        return redirect()->route('designation.list.view')->with('status', 'Deleted successfully!');
    }
}
