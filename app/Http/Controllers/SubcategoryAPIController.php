<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class SubcategoryAPIController extends Controller
{

    public function store(Request $req, $subscriberId){

        Session::put('subscriberId', $subscriberId);
        $messages = [
            'categoryid.required'  =>    "Category is required.",
            'subcategoryname.required'  =>    "Sub-category name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'categoryid' => 'required',
            'subcategoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $subcategory = new Subcategory;

            $subcategory->category_id                 = $req->categoryid;
            $subcategory->subcategory_name            = $req->subcategoryname;

            $subcategory->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Subcategory created Successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function list(Request $request, $subscriberId){

        $subcategory = Category::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
               ->where('categories.subscriber_id', $subscriberId)
               ->get();

        return response()->json($subcategory);
    }

    public function edit($id){
        $subcategory = Subcategory::find($id);

        $category = Category::find($subcategory->category_id);

        if($subcategory){
            return response()->json([
                'status'=>200,
                'subcategory'=>$subcategory,
                'category' => $category,

            ]);
        }
    }

    public function update(Request $req, $id){
        $messages = [
            'subcategoryname.required'  =>    "Sub-category name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'subcategoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $subcategory = Subcategory::find($id);
            $subcategory->subcategory_name                 = $req->subcategoryname;

            $subcategory->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Subcategory Data Updated Successfully'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);
    }

    public function destroy($id){
        Subcategory::find($id)->delete($id);

        return redirect('subcategory-list')->with('status', 'Deleted successfully!');
    }
}
