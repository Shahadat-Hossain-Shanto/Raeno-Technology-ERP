<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Price;
use App\Models\Store;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\ProductUnit;
use App\Models\Subcategory;
use App\Models\ProductSerial;
use App\Models\StoreInventory;
use Illuminate\Support\Carbon;
use App\Models\ProductInHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class ImportProductStore implements ToModel
class ImportProductStore implements ToCollection, WithHeadingRow
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
        $store=session()->get('productInStore');
        Validator::make($rows->toArray(), [
            '*.product_name' => 'required',
            '*.label' => 'required',
            '*.brand' => 'required',
            '*.category_name' => 'required',
            '*.variant_name' => 'required',
            '*.variant_measurement' => 'required',
            '*.price' => 'required',
            '*.mrp' => 'required',
            '*.stock' => 'required',
            '*.safety_stock' => 'required',

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

            if($row['barcode']==''){
                $barcode='N/A';
            }
            else{
                $barcode=$row['barcode'];
            }

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
                if(empty($subcategory) &&  $row['subcategory_name'] != '')
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
                if($barcode=='N/A' && $product->barcode!=''){
                    $barcode=$product->barcode;
                }
                $variant = Variant::where([
                    ['variant_name',$row['variant_name']],
                    ['product_id',$product->id],
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

            if($row['type']=='Serialize' && $row['serial_number']!='' || $row['type']!='Serialize'){
                $serialExist=0;
                if($row['type']=='Serialize' && $row['serial_number']!=''){
                    $variant = Variant::where([
                        ['variant_name',$row['variant_name']],
                        ['product_id',$product->id],
                        ['subscriber_id',Auth::user()->subscriber_id],
                        ])->first();

                    $serial = ProductSerial::where([
                        ['productId', $product->id],
                        ['variantId', $variant->id],
                        ['serialNumber', $row['serial_number']]
                    ])->get();

                    if($serial->isNotEmpty()){
                        $serialExist=1;
                    }
                    else{
                        $productSerial = new ProductSerial;
                        $productSerial->productId       = $product->id;
                        $productSerial->productName     = $product->productName;
                        $productSerial->variantId       = $variant->id;
                        $productSerial->variantName     = $variant->variant_name;
                        $productSerial->storeId         = $store;
                        $productSerial->serialNumber    = $row['serial_number'];
                        $productSerial->purchaseId      =  0;
                        $timestamp = Carbon::now();
                        $date = $timestamp->toDateString();
                        $productSerial->purchaseDate    = $date;
                        $productSerial->subscriber_id   =  Auth::user()->subscriber_id;
                        $productSerial->save();
                    }
                }
                elseif($row['type']!='Serialize'){
                    $variant = Variant::where([
                        ['variant_name',$row['variant_name']],
                        ['product_id',$product->id],
                        ['subscriber_id',Auth::user()->subscriber_id],
                        ])->first();

                    $serial = ProductSerial::where([
                        ['productId', $product->id],
                        ['variantId', $variant->id],
                        ['storeId', $store],
                        // ['serialNumber', $barcode]
                    ])->get();

                    if($serial->isEmpty()){
                        $productSerial = new ProductSerial;
                        $productSerial->productId       = $product->id;
                        $productSerial->productName     = $product->productName;
                        $productSerial->variantId       = $variant->id;
                        $productSerial->variantName     = $variant->variant_name;
                        $productSerial->storeId         = $store;
                        $productSerial->serialNumber    = $barcode;
                        $productSerial->purchaseId      =  0;
                        $timestamp = Carbon::now();
                        $date = $timestamp->toDateString();
                        $productSerial->purchaseDate    = $date;
                        $productSerial->subscriber_id   =  Auth::user()->subscriber_id;
                        $productSerial->save();
                    }
                    else{
                        foreach($serial AS $serial){
                            $productSerialId=$serial->id;
                        }
                        $productSerial=ProductSerial::find($productSerialId);
                        if($barcode!='N/A' || $productSerial->serialNumber==''){
                            $productSerial->serialNumber = $barcode;
                            $productSerial->save();
                        }
                    }
                }

                if($serialExist==0){
                    if($store==0){
                        $variant = Variant::where([
                            ['variant_name',$row['variant_name']],
                            ['product_id',$product->id],
                            ['subscriber_id',Auth::user()->subscriber_id],
                        ])->first();
                        $InventoryProduct = Inventory::where([
                            ['productId',$product->id],
                            ['variant_id',$variant->id],
                            ['subscriber_id',Auth::user()->subscriber_id],
                            ])->first();
                        if(empty($InventoryProduct))
                        {
                            $mrp=0;
                            $price=0;

                            Inventory::create([
                                'onHand'            => $row['stock'],
                                'productIncoming'   => $row['stock'],
                                'mrp'               => $row['mrp'],
                                'price'             => $row['price'],
                                'created_by'        => Auth::user()->subscriber_id,
                                'subscriber_id'        => Auth::user()->subscriber_id,
                                'productId'         => $product->id,
                                'variant_id'        => $variant->id,
                                'variant_name'      => $row['variant_name'],
                                'safety_stock'      => $row['safety_stock']
                            ]);

                            $history = new ProductInHistory;
                            $history->store = $store;
                            $history->store_name = 'Inventory';
                            $history->product = $product->id;
                            $history->product_name = $product->productName;
                            $history->quantity = $row['stock'];
                            $history->unit_price = $row['price'];
                            $history->mrp = $row['mrp'];
                            $history->subscriber_id = Auth::user()->subscriber_id;
                            $history->user_id = Auth::user()->id;
                            $history->product_in_num = 0;
                            $history->variant_id = $variant->id;
                            $history->variant_name = $row['variant_name'];

                            $history->save();


                            if( $mrp != $row['mrp'] || $price != $row['price'] ){

                                $price = new Price;
                                $price->product_id = $product->id;
                                $price->price = $row['price'];
                                $price->mrp = $row['mrp'];
                                $price->quantity = $row['stock'];
                                $price->store_id = $store;
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->product_in_num = 0;
                                $price->variant_id = $variant->id;
                                $price->variant_name = $row['variant_name'];
                                $price->save();
                            }
                        }
                        else{
                            $mrp=$InventoryProduct->mrp;
                            $price=$InventoryProduct->price;
                            $onHand = $InventoryProduct->onHand;
                            $productIncoming = $InventoryProduct->productIncoming;

                            $InventoryProduct->onHand = $onHand+$row['stock'];
                            $InventoryProduct->productIncoming = $productIncoming+$row['stock'];
                            $InventoryProduct->mrp = $row['mrp'];
                            $InventoryProduct->price = $row['price'];
                            $InventoryProduct->safety_stock = $row['safety_stock'];
                            $InventoryProduct->save();

                            $history = new ProductInHistory;
                            $history->store = $store;
                            $history->store_name = 'Inventory';
                            $history->product = $product->id;
                            $history->product_name = $product->productName;
                            $history->quantity = $row['stock'];
                            $history->unit_price = $row['price'];
                            $history->mrp = $row['mrp'];
                            $history->subscriber_id = Auth::user()->subscriber_id;
                            $history->user_id = Auth::user()->id;
                            $history->product_in_num = 0;
                            $history->variant_id = $variant->id;
                            $history->variant_name = $row['variant_name'];

                            $history->save();


                            if( $mrp != $row['mrp'] || $price != $row['price'] ){

                                $price = new Price;

                                $price->product_id = $product->id;
                                $price->price = $row['price'];
                                $price->mrp = $row['mrp'];
                                $price->quantity = $row['stock'];
                                $price->store_id = $store;
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->product_in_num = 0;
                                $price->variant_id = $variant->id;
                                $price->variant_name = $row['variant_name'];
                                $price->save();
                            }
                        }
                    }
                    else{
                        $variant = Variant::where([
                            ['variant_name',$row['variant_name']],
                            ['product_id',$product->id],
                            ['subscriber_id',Auth::user()->subscriber_id],
                        ])->first();
                        $storeIn = Store::find($store);
                        $storeInventory = StoreInventory::where([
                            ['productId',$product->id],
                            ['variant_id',$variant->id],
                            ['store_id',$store]
                            ])->first();
                        if(empty($storeInventory))
                        {
                            $mrp=0;
                            $price=0;

                            StoreInventory::create([
                                'onHand'            => $row['stock'],
                                'productIncoming'   => $row['stock'],
                                'mrp'               => $row['mrp'],
                                'price'             => $row['price'],
                                'created_by'        => Auth::user()->subscriber_id,
                                'productId'         => $product->id,
                                'variant_id'        => $variant->id,
                                'variant_name'      => $row['variant_name'],
                                'safety_stock'      => $row['safety_stock'],
                                'store_id'          => $store
                            ]);

                            $history = new ProductInHistory;
                            $history->store = $store;
                            $history->store_name = $storeIn->store_name;
                            $history->product = $product->id;
                            $history->product_name = $product->productName;
                            $history->quantity = $row['stock'];
                            $history->unit_price = $row['price'];
                            $history->mrp = $row['mrp'];
                            $history->subscriber_id = Auth::user()->subscriber_id;
                            $history->user_id = Auth::user()->id;
                            $history->product_in_num = 0;
                            $history->variant_id = $variant->id;
                            $history->variant_name = $row['variant_name'];

                            $history->save();


                            if( $mrp != $row['mrp'] || $price != $row['price'] ){

                                $price = new Price;

                                $price->product_id = $product->id;
                                $price->price = $row['price'];
                                $price->mrp = $row['mrp'];
                                $price->quantity = $row['stock'];
                                $price->store_id = $store;
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->product_in_num = 0;
                                $price->variant_id = $variant->id;
                                $price->variant_name = $row['variant_name'];
                                $price->save();
                            }
                        }
                        else{
                            $mrp=$storeInventory->mrp;
                            $price=$storeInventory->price;
                            $onHand = $storeInventory->onHand;
                            $productIncoming = $storeInventory->productIncoming;

                            $storeInventory->onHand = $onHand+$row['stock'];
                            $storeInventory->productIncoming = $productIncoming+$row['stock'];
                            $storeInventory->mrp = $row['mrp'];
                            $storeInventory->price = $row['price'];
                            $storeInventory->variant_name = $row['variant_name'];
                            $storeInventory->safety_stock = $row['safety_stock'];
                            $storeInventory->save();

                            $history = new ProductInHistory;
                            $history->store = $store;
                            $history->store_name = $storeIn->store_name;
                            $history->product = $product->id;
                            $history->product_name = $product->productName;
                            $history->quantity = $row['stock'];
                            $history->unit_price = $row['price'];
                            $history->mrp = $row['mrp'];
                            $history->subscriber_id = Auth::user()->subscriber_id;
                            $history->user_id = Auth::user()->id;
                            $history->product_in_num = 0;
                            $history->variant_id = $variant->id;
                            $history->variant_name = $row['variant_name'];

                            $history->save();


                            if( $mrp != $row['mrp'] || $price != $row['price'] ){

                                $price = new Price;

                                $price->product_id = $product->id;
                                $price->price = $row['price'];
                                $price->mrp = $row['mrp'];
                                $price->quantity = $row['stock'];
                                $price->store_id = $store;
                                $price->subscriber_id = Auth::user()->subscriber_id;
                                $price->product_in_num = 0;
                                $price->variant_id = $variant->id;
                                $price->variant_name = $row['variant_name'];
                                $price->save();
                            }
                        }
                    }
                }
            }
        }
        session()->forget('productInStore');
    }
}
