<?php

namespace App\Http\Controllers;

use Image;

use App\Models\Vat;
use App\Models\Leaf;
use App\Models\Batch;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\DeviceModel;
use App\Models\Discount;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Manufacturer;
use App\Models\ProductUnit;
use App\Models\Subcategory;
use App\Models\ProductImage;
use App\Models\StoreProduct;
use App\Models\VariantImage;
use Illuminate\Http\Request;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function create(){
        $categories = Category::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $subcategories = Subcategory::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $brands = Brand::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $products = Product::join('variants', 'products.id', 'variants.product_id')
        ->where('products.subscriber_id', Auth::user()->subscriber_id)
        ->get();

        // Log::info($products);

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $discounts = Discount::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $taxs = Vat::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $manufacturers = Manufacturer::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $models = DeviceModel::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $units = ProductUnit::where('subscriber_id', Auth::user()->subscriber_id)->get();

        return view('product/product-add-new', ['categories' => $categories,
            'units' => $units, 'suppliers' => $suppliers, 'stores' => $stores, 'brands' => $brands, 'products' => $products, 'discounts'=> $discounts, 'tax'=>$taxs, 'stores'=>$stores, 'manufacturers'=>$manufacturers, 'models'=>$models]);

    }

    public function showSubcategory($id){
        if($id!='0'){
            $subcategory = Subcategory::where('category_id', $id)->get();
            $category = Category::find($id)->category_name;}
        else{
            $subcategory ='0';
            $category ='0';
        }

        if($subcategory && $category){
            return response()->json([
                'status'=>200,
                'subcategory'=>$subcategory,
                'category'=>$category,
            ]);
        }
    }

    public function isTaxExcluded($id){

        $tax = Vat::where([
                ['taxName', $id],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->get();

        if($tax){
            return response()->json([
                'status'=>200,
                'tax'=>$tax,
            ]);
        }
    }

    public function showDiscount($id){

        $discount = Discount::where([
                ['id', $id],
                ['subscriber_id', Auth::user()->subscriber_id]
            ])->get();

        if($discount){
            return response()->json([
                'status'=>200,
                'discount'=>$discount,
            ]);
        }
    }

    public function store(Request $req){

        //PRODUCT-----------------------------------------------------------------------------------------------------------------
        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $variants = Variant::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $messages = [
            'productName.required'    =>   "Product name is required.",
            // 'productLabel.required'   =>   "Product label is required.",
            'brand.required'      =>   "Brand name is required.",
            'category.required'     =>     "Category is required.",
            'type.required'     =>   "Type is required."


        ];

        $validator = Validator::make($req->all(), [
            'productName'    => 'required',
            // 'productLabel'   => 'required',
            'brand'          => 'required',
            'category'      =>  'required',
            'type'      => 'required'
        ], $messages);

        if ($validator->passes()) {
            $product = Product::where([
                ['productName', $req->productName],
                // ['productLabel', $req->productLabel],
                ['brand', $req->brand],
                ['category', $req->category],
                ])->get();

            if($product->isEmpty())
            {
                $product = new Product;

                $product->productName               = $req->productName;
                // $product->productLabel              = $req->productLabel;
                $product->brand                     = $req->brand;
                $product->manufacturer              = $req->manufacturer;
                $product->model                     = $req->model;

                $product->category                  = $req->category;
                $product->category_name             = $req->category_name;

                $product->subcategory               = $req->subcategory;
                $product->subcategory_name          = $req->subcategory_name;

                $product->type                      = $req->type;
                $product->sku                       = $req->sku;
                // $product->barcode                   = $req->barcode;
                // $product->supplier	                = $req->supplier;

                $product->discount_type             = $req->discount_type;
                $product->available_discount        = $req->available_discount;

                if(doubleval($req->discount) == NULL){
                    $product->discount      = 0;
                }else{
                    $product->discount                  = doubleval($req->discount);
                }

                $product->offerItemId               = $req->offerItemId;
                $product->available_offer           = $req->available_offer;
                $product->freeItemName              = $req->freeItemName;

                if(doubleval($req->requiredQuantity) == NULL){
                    $product->requiredQuantity      = 0;
                }else{
                    $product->requiredQuantity      = doubleval($req->requiredQuantity);
                }

                if(doubleval($req->freeQuantity) == NULL){
                    $product->freeQuantity          = 0;
                }else{
                    $product->freeQuantity          = doubleval($req->freeQuantity);
                }

                $product->taxName                   = $req->taxName;
                $product->isExcludedTax             = $req->isExcludedTax;

                if(doubleval($req->tax) == NULL){
                    $product->tax      = 0;
                }else{
                    $product->tax                       = doubleval($req->tax);
                }

                $product->subscriber_id             = Auth::user()->subscriber_id;

                // $product->productImage              = $req->productImage;

                $product->save();


                // $productImage = new ProductImage;
                // if ($req -> hasFile('imagefile')) {
                //     $file = $req -> file ('imagefile');
                //     $extension = $file->getClientOriginalExtension();
                //     $size = $file->getSize();
                //     // $filename = time() . '.' .$file->getClientOriginalName();
                //     $filename = $file->getClientOriginalName();
                //     $file->move('uploads/products/', $filename);
                //     $productImage->imageName  = $filename;

                //     $productImage->imageExtension = $extension;
                //     $productImage->imageSize = $size;

                //     // $productImage->save();

                // }
                // $productImage->save();



                foreach($req->variants as $variant){
                    // Log::info($variant);
                    $newVariant = new Variant;
                    $newVariant->variant_name = $variant['variantName'];
                    $newVariant->variant_measurement = $variant['variantMeasurement'];
                    $newVariant->variant_description = $variant['variantDescription'];
                    // $newVariant->available_discount = $variant['available_discount'];
                    // $newVariant->discount_type = $variant['discount_type'];
                    // $newVariant->discount = $variant['discount'];
                    // $newVariant->taxName = $variant['taxName'];
                    // $newVariant->isExcludedTax = $variant['isExcludedTax'];
                    // $newVariant->tax = $variant['tax'];
                    // $newVariant->variant_image = $variant['variantimage'];
                    $newVariant->product_id = $product->id;
                    $newVariant->subscriber_id = Auth::user()->subscriber_id;
                    $newVariant->save();
                }
                return response() -> json([
                    'status'=> 200,
                    'productId' => $product->id,
                    'message' => 'Product added Successfully!'
                ]);
            }
            else{
                return response() -> json([
                    'status'=> 400,
                    'message' => 'Product Already Exist!'
                ]);
            }
            // return view('product/product-stock', [ 'stores' => $stores, 'variants' => $variants, 'productId'=>$product->id ]);
            // return redirect('product-stock-create')->with('productId', $product->id);
        }

        return response()->json(['error'=>$validator->errors()]);

    }

    public function imageStore(Request $request){
        $productImage = new ProductImage;

        if ($request -> hasFile('imagefile')) {
            // $file = $request -> file ('imagefile');
            // $extension = $file->getClientOriginalExtension();
            // $size = $file->getSize();
            // // $filename = time() . '.' .$file->getClientOriginalName();
            // $filename = $file->getClientOriginalName();
            // $file->move('uploads/products/', $filename);
            // $productImage->imageName  = $filename;

            // $productImage->imageExtension = $extension;
            // $productImage->imageSize = $size;

            // $productImage->save();

            //-------------------------------------------------------------

            Log::info("HasFile");
            $image = $request->file('imagefile');
            $input['imagename'] = time().'.'.$image->extension();

            $destinationPath = public_path('/uploads/products/');
            $img = Image::make($image->path());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);

            $destinationPath = public_path('/uploads/products/');
            $image->move($destinationPath, $input['imagename']);

            $productImage->imageName  = $input['imagename'];
            $productImage->imageExtension = $image->extension();
            $productImage->imageSize = $image->getSize();
            // $productImage->save();
        }
        $productImage->save();

        return response() -> json([
            'status'=>200,
            'imageName' => $filename,
            'message' => 'Product Image created Successfully!'
        ]);

    }

    public function imageUpdate(Request $request, $id){

       $imageId = Product::join('product_images', 'products.productImage', '=', 'product_images.imageName')
                            ->where([
                                ['products.id', $id],
                                ['products.subscriber_id', Auth::user()->subscriber_id]
                            ])
                            ->get(['product_images.id']);

       if($imageId->isEmpty()){

            return response() -> json([
                'status'=>200,
                'message' => 'Not Found'
            ]);

        }else{

            foreach($imageId as $p){
                $i =  $p->id;
            }

            $productImage = ProductImage::find($i);

            if ($request -> hasFile('imagefile')) {

                $path = 'uploads/products/'.$productImage->imageName;
                if(File::exists($path)){
                    File::delete($path);
                }

                $file = $request -> file ('imagefile');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();
                $file->move('uploads/products/', $filename);
                $productImage->imageName  = $filename;

                $productImage->imageExtension = $extension;
                $productImage->imageSize = $size;

            }

            $productImage->save();

            $product = Product::find($id);
            $product->productImage = $filename;
            $product->save();

            return response() -> json([
                'status'=>200,
                'imageName' => $filename,
                'message' => 'Product Image updated Successfully!'
            ]);
        }

    }


    public function listView(){
        return view('product/product-list');
    }

    public function list(Request $request){
        $data = Product::where('products.subscriber_id', Auth::user()->subscriber_id)->get();
        if($request -> ajax()){
            return response()->json([
                'product'=>$data,
            ]);
        }

    }


    public function edit(Request $request, $id){
        $categories = Category::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $subcategories = Subcategory::all();
        $brands = Brand::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $products = Product::where('products.id', $id)->get();

        // Log::info($products);

        $suppliers = Supplier::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $tax = Vat::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $batches = Batch::where('subscriber_id', Auth::user()->subscriber_id)->get();
        // $leaves = Leaf::where('subscriber_id', Auth::user()->subscriber_id)->get();

        $units = ProductUnit::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $manufacturers = Manufacturer::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $models = DeviceModel::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $p = Product::find($products[0]->id);

        // $data = Product::where([
        //                     ['products.id', $id],
        //                     ['products.subscriber_id', Auth::user()->subscriber_id]
        //                 ])
        //                 ->get();
        // Log::info($this->showSubcategory($data[0]->subcategory));
        // Log::info($data[0]->subcategory);

        if($request -> ajax()){
            return response()->json([
                'status'=>200,
                'product'=>$products,
            ]);
        }

        return view('product/product-edit', ['categories' => $categories, 'subcategories' => $subcategories,
            'units' => $units, 'brands' => $brands, 'products' => $products, 'p' => $p->id, 'v'=>$id, 'suppliers' => $suppliers, 'tax' => $tax, 'manufacturers'=>$manufacturers, 'models'=>$models ]);
    }

    public function update(Request $req, $id){
        // log::info($req);
        $messages = [
            'productname.required'    =>   "Product name is required.",
            // 'productlabel.required'   =>   "Product label is required.",
            'productbrand.required'      =>   "Brand name is required.",
            'categoryid.required'     =>   "Category is required.",
            'type.unique'       =>   "Product already exists."
        ];

        $validator = Validator::make($req->all(), [
            'productname'    => 'required',
            // 'productlabel'   => 'required',
            'productbrand'      => 'required',
            'categoryid'     => 'required',
            'type'     => 'required',
        ], $messages);

        if ($validator->passes()) {

            $product = Product::find($id);

            //PRODUCT-----------------------------------------------------------------------------------------------------------------

            $product->productName               = $req->productname;
            // $product->productLabel              = $req->productlabel;
            $product->brand                     = $req->productbrand;
            $product->model         = $req->productmodel;
            $product->manufacturer  = $req->productmanufacturer;
            $product->category                  = $req->categoryid;

            $category = Category::find($req->categoryid);
            $product->category                  = $category->id;
            $product->category_name             = $category->category_name;

            if($req->subcategoryname!=null){
                $subcategory = Subcategory::find($req->subcategoryname);
                // Log::info($subcategory);
                $product->subcategory           = $subcategory->id;
                $product->subcategory_name      = $subcategory->subcategory_name;
            }


            $product->type                      = $req->type;
            $product->sku                       = $req->sku;
            // $product->barcode                   = $req->barcode;
            // $product->supplier	                = $req->supplier;



            // $product->color                     = $req->color;
            // $product->size                      = $req->size;



            // $product->offerItemId               = $req->offeritemid;
            // $product->available_offer           = $req->availableoffer;

            // $freeItemName = Variant::find($req->offeritemid);
            // if($freeItemName){
            //     $freeItemNameX = Product::join('variants', 'products.id', 'variants.product_id')
            //     ->where([
            //         ['variants.id', $freeItemName->id]
            //         ])->first();

            //     $freeItem = $freeItemNameX->productName.'('.$freeItemNameX->variant_name.')';

            // }

            // // Log::info($freeItemNameX);


            // if($freeItemName == null){
            //     $freeItem = 'NULL';
            // }else{
            //     $product->freeItemName = $freeItem;
            // }



            // if(doubleval($req->requiredquantity) == NULL){
            //     $product->requiredQuantity      = 0;
            // }else{
            //     $product->requiredQuantity      = doubleval($req->requiredquantity);
            // }

            // if(doubleval($req->freequantity) == NULL){
            //     $product->freeQuantity          = 0;
            // }else{
            //     $product->freeQuantity          = doubleval($req->freequantity);
            // }
            $product->subscriber_id             = Auth::user()->subscriber_id;

            // $product->productImage             = $req->productImage;




            // $product->discount_type             = $req->discounttype;
            // $product->available_discount        = $req->availablediscount;

            // if(doubleval($req->discount) == NULL){
            //     $product->discount      = 0;
            // }else{
            //     $product->discount                  = doubleval($req->discount);
            // }
            // $product->taxName                   = $req->taxname;
            // $product->isExcludedTax             = $req->taxexcluded;

            // if(doubleval($req->tax) == NULL){
            //     $product->tax      = 0;
            // }else{
            //     $product->tax                       = doubleval($req->tax);
            // }




            $product->save();

            // $variantX = Variant::find($req->variantid);
            // $variantX->variant_name         = $req->variantname;
            // $variantX->variant_measurement  = $req->measurement;
            // $variantX->variant_description  = $req->variantdescription;
            // if ($req->hasFile('variantimage')) {
            // $file = $req -> file ('variantimage');
            // $filename = $file->getClientOriginalName();
            // // $file->move('uploads/variants/', $filename);
            // $variantX->variant_image        = $filename;
            // }
            // $variantX->discount_type        = $req->discounttype;
            // $variantX->available_discount   = $req->availablediscount;

            // if(doubleval($req->discount) == NULL){
            //     $variantX->discount      = 0;
            // }else{
            //     $variantX->discount                  = doubleval($req->discount);
            // }
            // $variantX->taxName                   = $req->taxname;
            // $variantX->isExcludedTax             = $req->taxexcluded;

            // if(doubleval($req->tax) == NULL){
            //     $variantX->tax      = 0;
            // }else{
            //     $variantX->tax                       = doubleval($req->tax);
            // }
            // $variantX->save();

            // $storeproducts = StoreInventory::where([
            //     // ['variant_id', $req->variantid],
            //     ['productId', $id],
            //     ])->get();
            // if(isset($storeproducts)){
            //     // Log::info($storeproducts);
            //     foreach($storeproducts as $storeproduct)
            //     {
            //     // $storeproduct->variant_name = $req->variantname;
            //     $storeproduct->save();
            //     }
            // }
            // $inventoryproduct = Inventory::where([
            //     // ['variant_id', $req->variantid],
            //     ['productId', $id],
            //     ])->first();
            // if(isset($inventoryproduct)){
            //     // Log::info($inventoryproduct);
            //     // $inventoryproduct->variant_name = $req->variantname;
            //     $inventoryproduct->save();
            // }


            // $imageId = Product::join('product_images', 'products.productImage', '=', 'product_images.imageName')
            //                 ->where([
            //                     ['products.id', $id],
            //                     ['products.subscriber_id', Auth::user()->subscriber_id]
            //                 ])
            //                 ->get(['product_images.id']);


            // Log::info($req->variantimage);
            // Log::info($req->variantdescription);

            // if($req->variantimage->isEmpty()){

            //     $VariantImageId = Variant::join('variant_images', 'variants.variant_image', '=', 'variant_images.image_name')
            //         ->where([
            //             ['variants.id', $req->variantid],
            //             ['variants.subscriber_id', Auth::user()->subscriber_id]
            //         ])
            //         ->get(['variant_images.id']);

            //         foreach($VariantImageId as $p){
            //             $i =  $p->id;
            //         }

            //         $variantImage = VariantImage::find($i);

            //         if ($req -> hasFile('variantimage')) {

            //             $path = 'uploads/variants/'.$variantImage->image_name;
            //             if(File::exists($path)){
            //                 File::delete($path);
            //             }

            //             $file = $req -> file ('variantimage');
            //             $extension = $file->getClientOriginalExtension();
            //             $size = $file->getSize();
            //             // $filename = time() . '.' .$file->getClientOriginalName();
            //             $filename = $file->getClientOriginalName();
            //             $file->move('uploads/variants/', $filename);
            //             $variantImage->image_name  = $filename;

            //             $variantImage->image_extension = $extension;
            //             $variantImage->image_size = $size;

            //             $variantImage->save();

            //             $variant1 = Variant::find($req->variantid);
            //             $variant1->variant_image = $filename;
            //             $variant1->save();
            //         }


            // }
            // else{

            //     foreach($imageId as $p){
            //         $i =  $p->id;
            //     }

            //     $productImage = ProductImage::find($i);

            //     if ($req -> hasFile('imagefile')) {

            //         $path = 'uploads/products/'.$productImage->imageName;
            //         if(File::exists($path)){
            //             File::delete($path);
            //         }

            //         $file = $req -> file ('imagefile');
            //         $extension = $file->getClientOriginalExtension();
            //         $size = $file->getSize();
            //         // $filename = time() . '.' .$file->getClientOriginalName();
            //         $filename = $file->getClientOriginalName();
            //         $file->move('uploads/products/', $filename);
            //         $productImage->imageName  = $filename;

            //         $productImage->imageExtension = $extension;
            //         $productImage->imageSize = $size;
            //         $productImage->save();

            //         $product1 = Product::find($id);
            //         $product1->productImage = $filename;
            //         $product1->save();
            //     }

            // }

            //---------------------VARIANT IMAGE



            if($req->type!='Serialize'){
                $barcodes=ProductSerial::where([['productId', $id], ['subscriber_id', Auth::user()->subscriber_id]])->get();
                if($barcodes->isNotEmpty()){
                    foreach($barcodes AS $barcode){
                        $identificationNumber=ProductSerial::find($barcode->id);
                        $identificationNumber->serialNumber=$req->barcode;
                        $identificationNumber->save();
                    }
                }
            }

            return response() -> json([
                'status'=>200,
                'message' => 'Product updated successfully!'
            ]);
        }

        return response()->json(['error'=>$validator->errors()]);
    }

    public function destroy($id){
        $product = Product::find($id);

        $storeProduct = StoreInventory::where('productId',$id)->get();
        $inventoryProduct = Inventory::where('productId',$id)->get();

        if(count($storeProduct)==0 && count($inventoryProduct)==0)
        {
            $productVariant = Variant::where('product_Id',$id)->get();
            if(count($productVariant)==0){
                Product::find($id)->delete($id);
                return response() -> json([
                    'status'=>200,
                    'message' => 'Product Deleted'
                ]);
            }
            else{
                return response() -> json([
                    'status'=>400,
                    'message' => 'Product has Variant!'
                ]);
            }
        }
        else{
            return response() -> json([
                'status'=>400,
                'message' => 'Product is in Inventory!'
            ]);
        }
    }

    public function variantImageStore(Request $request){
        // log::info($request->index);
        if($request['variantImage0']=='undefined')
        {
            return response() -> json([
                'status'=>200,
                'message' => 'Variant Image created Successfully!'
            ]);
        }
        // log::info($request -> file ('variantImage2'));

        for($i=0;$i<$request->index;$i++)
        {
            if ($request -> hasFile('variantImage'.$i)) {
                $productImage = new VariantImage;

                $file = $request -> file ('variantImage'.$i);
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();
                $file->move('uploads/variants/', $filename);
                $productImage->image_name  = $filename;

                $productImage->image_extension = $extension;
                $productImage->image_size = $size;
                $productImage->subscriber_id = Auth::user()->subscriber_id;

                $productImage->save();
            }
        }

        // $productImage = new VariantImage;

        // if ($request -> hasFile('variantImage')) {
        //     $file = $request -> file ('variantImage');
        //     $extension = $file->getClientOriginalExtension();
        //     $size = $file->getSize();
        //     // $filename = time() . '.' .$file->getClientOriginalName();
        //     $filename = $file->getClientOriginalName();
        //     $file->move('uploads/variants/', $filename);
        //     $productImage->image_name  = $filename;

        //     $productImage->image_extension = $extension;
        //     $productImage->image_size = $size;
        //     $productImage->subscriber_id = Auth::user()->subscriber_id;

        //     $productImage->save();
        // }

        return response() -> json([
            'status'=>200,
            // 'imageName' => $filename,
            'message' => 'Variant Image created Successfully!'
        ]);
    }

    public function variantImageUpdate(Request $request, $variantimage)
    {
        $image = new VariantImage;
        $image = VariantImage::where([
                ['image_name', $request->file('variantImage')->getClientOriginalName()]
                ])->get();

        if ($request->hasFile('variantImage')) {
            $path = 'uploads/variants/' . $variantimage;
                if(File::exists($path)){
                    File::delete($path);
                }
        if($image){
            $file = $request->file('variantImage');
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            // $filename = time() . '.' .$file->getClientOriginalName();
            $filename = $file->getClientOriginalName();
            $file->move('uploads/variants/', $filename);

            $image->image_name  = $filename;
            $image->image_extension = $extension;
            $image->image_size = $size;
            $image->subscriber_id = Auth::user()->subscriber_id;

            log::info($image);
            $image->save();
        }
        }

        return response()->json([
            'status' => 200,
            'imageName' => $filename,
            'message' => 'Variant Image created Successfully!'
        ]);
    }

}
