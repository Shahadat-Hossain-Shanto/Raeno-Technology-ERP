<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function create(){
        return view('brand/brand-add');
    }

    public function store(Request $req){

        $messages = [
            'brandname.required'  =>    "Brand name is required.",
            'brandorigin.required'  =>    "Brand origin is required.",
        ];

        $validator = Validator::make($req->all(), [
            'brandname' => 'required',
            'brandorigin' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $brand = new Brand;

            $brand->subscriber_id                 = Auth::user()->subscriber_id;
            $brand->brand_name                    = $req->brandname;
            $brand->brand_origin                    = $req->brandorigin;

            // if ($req -> hasFile('brandlogo')) {
            //     $file = $req -> file ('brandlogo');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' .$extension;
            //     $file->move('uploads/brands/', $filename);
            //     $brand->brand_logo = $filename;
            // }

            $brand->save();
            return response() -> json([
                'status'=>200,
                'message' => 'Brand created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function listView(){
        return view('brand/brand-list');
    }

    public function list(Request $request){

        $columns = array(
                        0 =>'brand_name',
                        1 =>'brand_origin',
                        2=> 'brand_logo',
                        3=> 'id',
                    );

        $totalData = Brand::where('subscriber_id', Auth::user()->subscriber_id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $brands = Brand::where('subscriber_id', Auth::user()->subscriber_id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $brands =  Brand::where('subscriber_id', Auth::user()->subscriber_id)
                            ->where('brand_name','LIKE',"%{$search}%")
                            ->orWhere('brand_origin', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Brand::where('subscriber_id', Auth::user()->subscriber_id)
                            ->where('brand_name','LIKE',"%{$search}%")
                            ->orWhere('brand_origin', 'LIKE',"%{$search}%")
                            ->count();
        }



        $data = array();

        if(!empty($brands))
        {
            foreach ($brands as $brand)
            {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);

                $nestedData['brand_name'] = $brand->brand_name;
                $nestedData['brand_origin'] = $brand->brand_origin;
                $nestedData['brand_logo'] = $brand->brand_logo;
                $nestedData['id'] = $brand->id;

                $data[] = $nestedData;



            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                );

        // $brand = Brand::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return json_encode( $json_data);

        // if($request -> ajax()){
        //     return response()->json([
        //         'brand'=>$json_data
        //     ]);
        // }
    }

    public function edit($id){
        $brand = Brand::find($id);

        if($brand){
            return response()->json([
                'status'=>200,
                'brand'=>$brand,

            ]);
        }
    }

    public function update(Request $req, $id){

        $messages = [
            'brandname.required'  =>    "Brand name is required.",
            'brandorigin.required'  =>    "Brand origin is required.",
        ];

        $validator = Validator::make($req->all(), [
            'brandname' => 'required',
            'brandorigin' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $brand = Brand::find($id);

            $brand->brand_name                    = $req->brandname;
            $brand->brand_origin                    = $req->brandorigin;

            // if ($req -> hasFile('brandlogo')) {

            //     $path = 'uploads/brands/'.$brand->brand_logo;
            //     if(File::exists($path)){
            //         File::delete($path);
            //     }

            //     $file = $req -> file ('brandlogo');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' .$extension;
            //     $file->move('uploads/brands/', $filename);
            //     $brand->brand_logo = $filename;
            // }

            $brand->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Brand updated successfully'
            ]);
        }
        return response()->json(['error'=>$validator->errors()]);

    }

    public function destroy($id){
        Brand::find($id)->delete($id);

        return redirect('brand-list')->with('status', 'Deleted successfully!');
    }
}
