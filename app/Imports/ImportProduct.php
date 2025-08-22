<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductUnit;
use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class ImportProduct implements ToModel
class ImportProduct implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    // public function model(array $row)
    // {
    //     return new Product([
    //         //Variant Data
    //     ]);
    // }

    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), [
            '*.product_name' => 'required',
            '*.label' => 'required',
            '*.brand' => 'required',
            '*.category_name' => 'required',
            '*.variant_name' => 'required',
            '*.variant_measurement' => 'required',

         ])->validate();

        // Log::info($rows);

        foreach ($rows as $row)
        {
            $product = Product::where([
                ['productName',$row['product_name']],
                ['category_name',$row['category_name']],
                ['subcategory_name',$row['subcategory_name']],
                ['subscriber_id',Auth::user()->subscriber_id],
                ])->first();

            if(empty($product))
            {
                $brand = Brand::where([['brand_name',$row['brand']],['subscriber_id',Auth::user()->subscriber_id]])->first();
                if(empty($brand))
                {
                    $brand = new Brand;
                    $brand->brand_name = $row['brand'];
                    $brand->subscriber_id = Auth::user()->subscriber_id;
                    $brand->save();
                }

                $category = Category::where([['category_name',$row['category_name']],['subscriber_id',Auth::user()->subscriber_id]])->first();
                if(empty($category))
                {
                    $category = new Category;
                    $category->category_name = $row['category_name'];
                    $category->subscriber_id = Auth::user()->subscriber_id;
                    $category->save();
                }

                $subcategory = Subcategory::where([
                    ['subcategory_name',$row['subcategory_name']],
                    ['category_id',$category->id],
                    ])->first();
                if(empty($subcategory) && $row['subcategory_name'] != '')
                {
                    $subcategory = new Subcategory;
                    $subcategory->subcategory_name = $row['subcategory_name'];
                    $subcategory->category_id = $category->id;
                    $subcategory->save();
                    $subcategoryId = $subcategory->id;
                }
                else{
                    $subcategoryId = 0;
                }

                $supplier = Supplier::where([['name',$row['supplier']],['subscriber_id',Auth::user()->subscriber_id]])->first();
                if(empty($supplier) && $row['supplier'] != '')
                {
                    $supplier = new Supplier;
                    $supplier->name = $row['supplier'];
                    $supplier->subscriber_id = Auth::user()->subscriber_id;

                    $suppliers = Supplier::where([
                        ['subscriber_id', Auth::user()->subscriber_id]
                    ])->first();

                   if($suppliers){
                        $suppliers = Supplier::where([
                            ['subscriber_id', Auth::user()->subscriber_id]
                        ])->latest()->first();
                        $supplier->head_code = (int)$suppliers->head_code + 1;
                    }else{
                        $supplier->head_code = (50101 * 1000) + 1;
                    }

                    $supplier->save();
                }

                $unit = ProductUnit::where([['name',$row['variant_measurement']],['subscriber_id', Auth::user()->subscriber_id]])->first();
                if(empty($unit))
                {
                    $unit = new ProductUnit;
                    $unit->name = $row['variant_measurement'];
                    $unit->description = $row['variant_description'];
                    $unit->subscriber_id = Auth::user()->subscriber_id;
                    $unit->user_id = Auth::user()->id;
                    $unit->save();
                }

                if($row['type']=='Serialize'){
                    $productType='Serialize';
                }
                else{
                    $productType='Non-Serialize';
                }

                if($row['variant_description']==''){
                    $variant_description='N/A';
                }
                else{
                    $variant_description=$row['variant_description'];
                }

                $product = Product::create([
                    'productName'       => $row['product_name'],
                    'productLabel'      => $row['label'],
                    'brand'             => $row['brand'],
                    'category'          => $category->id,
                    'category_name'     => $row['category_name'],
                    'subcategory'       => $subcategoryId,
                    'subcategory_name'  => $row['subcategory_name'],
                    'type'              => $productType,
                    'sku'               => $row['sku'],
                    'barcode'           => $row['barcode'],
                    'supplier'          => $row['supplier'],
                    'subscriber_id'     => Auth::user()->subscriber_id,
                ]);

                Variant::create([
                    'variant_name'          => $row['variant_name'],
                    'variant_measurement'   => $row['variant_measurement'],
                    'variant_description'   => $variant_description,
                    'product_id'            => $product->id,
                    'discount_type'         => 'N/A',
                    'available_discount'    => 'N/A',
                    'discount'              => 0,
                    'taxName'               => 'N/A',
                    'isExcludedTax'         => 'N/A',
                    'tax'                   => 0,
                    'subscriber_id'         => Auth::user()->subscriber_id,
                ]);
            }
            else{
                $variant = Variant::where([
                    ['variant_name',$row['variant_name']],
                    ['product_id',$product->id,],
                    ['subscriber_id',Auth::user()->subscriber_id],
                    ])->first();
                if(empty($variant))
                {
                    if($row['variant_description']==''){
                        $variant_description='N/A';
                    }
                    else{
                        $variant_description=$row['variant_description'];
                    }
                    Variant::create([
                        'variant_name'          => $row['variant_name'],
                        'variant_measurement'   => $row['variant_measurement'],
                        'variant_description'   => $variant_description,
                        'product_id'            => $product->id,
                        'discount_type'         => 'N/A',
                        'available_discount'    => 'N/A',
                        'discount'              => 0,
                        'taxName'               => 'N/A',
                        'isExcludedTax'         => 'N/A',
                        'tax'                   => 0,
                        'subscriber_id'         => Auth::user()->subscriber_id,
                    ]);
                }
            }
        }
    }
}
