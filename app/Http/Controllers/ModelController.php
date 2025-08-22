<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ModelController extends Controller
{
    public function create(){
        return view('model/model-add');
    }

    public function store(Request $req){
        $messages = [
            'modelname.required'  => "Model name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'modelname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $model = new DeviceModel;

            $model->subscriber_id = Auth::user()->subscriber_id;
            $model->created_by    = Auth::user()->subscriber_id;
            $model->model_name    = $req->modelname;

            $model->save();

            return response()->json([
                'status'=>200,
                'message' => 'Model created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);
    }

    public function listView(){
        return view('model/model-list');
    }

    public function list(Request $request){

        $columns = array(
            0 =>'model_name',
            1 =>'id',
        );

        $totalData = DeviceModel::where('subscriber_id', Auth::user()->subscriber_id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $models = DeviceModel::where('subscriber_id', Auth::user()->subscriber_id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $models = DeviceModel::where('subscriber_id', Auth::user()->subscriber_id)
                ->where('model_name','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = DeviceModel::where('subscriber_id', Auth::user()->subscriber_id)
                ->where('model_name','LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($models))
        {
            foreach ($models as $model)
            {
                $nestedData['model_name'] = $model->model_name;
                $nestedData['id']         = $model->id;

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
        $model = DeviceModel::find($id);

        if($model){
            return response()->json([
                'status'=>200,
                'model'=>$model,
            ]);
        }
    }

    public function update(Request $req, $id){

        $messages = [
            'modelname.required'  => "Model name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'modelname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $model = DeviceModel::find($id);

            $model->model_name = $req->modelname;
            $model->updated_by = Auth::user()->subscriber_id;

            $model->save();

            return response()->json([
                'status'=>200,
                'message' => 'Model updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    public function destroy($id){
        DeviceModel::find($id)->delete();

        return redirect('model-list')->with('status', 'Deleted successfully!');
    }
}
