<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Image;


class CategoryController extends Controller
{
    public function create(){
        // $subscribers = Subscriber::all();
        return view('category/category-add');
    }

    public function store(Request $req){

        $messages = [
            'categoryname.required'  =>    "Category name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'categoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $category = new Category;

            $category->subscriber_id                 = Auth::user()->subscriber_id;
            $category->category_name                 = $req->categoryname;

            if ($req -> hasFile('categoryimage')) {

                $image = $req->file('categoryimage');
                $imageName = time().'.'.$image->extension();

                // $destinationPath = public_path('/thumbnail');
                $img = Image::make($image->path());
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $destinationPath = public_path('/uploads/categories');
                $image->move($destinationPath, $imageName);

                $category->category_image = $imageName;

            }

            $category->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Category created successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function listView(){
        return view('category/category-list');
    }

    public function list(Request $request){

        $category = Category::where('subscriber_id', Auth::user()->subscriber_id)->get();

        if($request -> ajax()){
            return response()->json([
                'category'=>$category,
            ]);
        }


    }

    public function edit($id){
        $category = Category::find($id);

        if($category){
            return response()->json([
                'status'=>200,
                'category'=>$category,

            ]);
        }
    }

    public function update(Request $req, $id){

        $messages = [
            'edit_categoryname.required'  =>    "Category name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'edit_categoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $category = Category::find($id);
            $category->category_name                 = $req->edit_categoryname;

            if ($req->hasFile('edit_categoryimage')) {

                $image = $req->file('edit_categoryimage');
                $imageName = time() . '.' . $image->extension();

                // $destinationPath = public_path('/thumbnail');
                $img = Image::make($image->path());
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $destinationPath = public_path('/uploads/categories');
                $image->move($destinationPath, $imageName);

                $category->category_image = $imageName;
            }


            $category->save();

            return response() -> json([
                'status'=>200,
                'message' => 'Category updated successfully'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function destroy($id){
        Category::find($id)->delete($id);

        return redirect('category-list')->with('status', 'Deleted successfully!');
    }
}
