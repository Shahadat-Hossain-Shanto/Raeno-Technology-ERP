<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Manufacturer;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ManufacturerController extends Controller
{
    public function create(){
        return view('manufacturer/manufacturer-add');
    }

    public function store(Request $req){
        $messages = [
            'manufacturername.required'  => "Manufacturer name is required.",
            'manufacturerorigin.required'  => "Manufacturer origin is required.",
        ];

        $validator = Validator::make($req->all(), [
            'manufacturername' => 'required',
            'manufacturerorigin' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $manufacturer = new Manufacturer;

            $manufacturer->subscriber_id        = Auth::user()->subscriber_id;
            $manufacturer->created_by           = Auth::user()->subscriber_id;
            $manufacturer->manufacturer_name    = $req->manufacturername;
            $manufacturer->manufacturer_origin  = $req->manufacturerorigin;

            $manufacturer->save();

            return response()->json([
                'status'=>200,
                'message' => 'Manufacturer created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);
    }

    public function listView(){
        return view('manufacturer/manufacturer-list');
    }

    public function list(Request $request){

        $columns = array(
            0 =>'manufacturer_name',
            1 =>'manufacturer_origin',
            2=> 'id',
        );

        $totalData = Manufacturer::where('subscriber_id', Auth::user()->subscriber_id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $manufacturers = Manufacturer::where('subscriber_id', Auth::user()->subscriber_id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $manufacturers = Manufacturer::where('subscriber_id', Auth::user()->subscriber_id)
                ->where('manufacturer_name','LIKE',"%{$search}%")
                ->orWhere('manufacturer_origin', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Manufacturer::where('subscriber_id', Auth::user()->subscriber_id)
                ->where('manufacturer_name','LIKE',"%{$search}%")
                ->orWhere('manufacturer_origin', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($manufacturers))
        {
            foreach ($manufacturers as $manufacturer)
            {
                $nestedData['manufacturer_name'] = $manufacturer->manufacturer_name;
                $nestedData['manufacturer_origin'] = $manufacturer->manufacturer_origin;
                $nestedData['id'] = $manufacturer->id;

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return json_encode($json_data);
    }

    public function edit($id){
        $manufacturer = Manufacturer::find($id);

        if($manufacturer){
            return response()->json([
                'status'=>200,
                'manufacturer'=>$manufacturer,
            ]);
        }
    }

    public function update(Request $req, $id){

        $messages = [
            'manufacturername.required'  => "Manufacturer name is required.",
            'manufacturerorigin.required'  => "Manufacturer origin is required.",
        ];

        $validator = Validator::make($req->all(), [
            'manufacturername' => 'required',
            'manufacturerorigin' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $manufacturer = Manufacturer::find($id);

            $manufacturer->manufacturer_name    = $req->manufacturername;
            $manufacturer->updated_by           = Auth::user()->subscriber_id;
            $manufacturer->manufacturer_origin  = $req->manufacturerorigin;

            $manufacturer->save();

            return response()->json([
                'status'=>200,
                'message' => 'Manufacturer updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    public function destroy($id){
        Manufacturer::find($id)->delete();

        return redirect('manufacturer-list')->with('status', 'Deleted successfully!');
    }
}
