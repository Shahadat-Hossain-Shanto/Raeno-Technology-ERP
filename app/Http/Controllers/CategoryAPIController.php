<?php

namespace App\Http\Controllers;

use Image;
use App\Models\Category;
use App\Models\Subscriber;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class CategoryAPIController extends Controller
{
    public function store(Request $req, $subscriberId){

        Session::put('subscriberId', $subscriberId);
        $messages = [
            'categoryname.required'  =>    "Category name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'categoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $category = new Category;

            $category->subscriber_id                 = $subscriberId;
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


    public function list(Request $request, $subscriberId){
        $category = Category::where('subscriber_id', $subscriberId)->get();

        return response()->json($category);
    }

    public function categorySubcategoryList(Request $request, $subscriberId){

        $categorys = Category::where('subscriber_id', $subscriberId)->get();
        $subcategorys = Category::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
            ->where('categories.subscriber_id', $subscriberId)
            ->get();

        $array=[];
        foreach($categorys AS $category){
            $x = [
                "categoryId" => $category->id,
                "categoryName" => $category->category_name
            ];
            $y=[];
            foreach($subcategorys AS $subcategory){
                if($subcategory->category_id==$category->id){
                    $sub=[];
                    $sub = [
                        "id" => $subcategory->id,
                        "name" => $subcategory->subcategory_name
                    ];
                    $y[] =  $sub;
                }
            }
            $x['subcategories'] =  $y;
            $array[] =  $x;
        }
        return response()->json($array);
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
