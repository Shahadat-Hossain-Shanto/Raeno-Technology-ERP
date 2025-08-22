<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportProduct implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'id',
            'product_name',
            'label',
            'brand',
            'category',
            'category_name',
            'subcategory',
            'subcategory_name',
            'type',
            'sku',
            'barcode',
            'supplier',
            'variant_id',
            'variant_name',
            'variant_measurement',
            'variant_description',
            'stock',
            'safety_stock',
            'mrp',
            'price',
            'serial_number',
        ];
    }

    public function collection()
    {
        // $data = Product::join('variants', 'products.id', 'variants.product_id')
        // ->select('products.productName', 'products.productLabel','products.brand','products.category_name','products.sku','products.barcode',
        //         'products.supplier', 'variants.variant_name','variants.variant_measurement')
        // ->where('products.subscriber_id', Auth::user()->subscriber_id)
        // ->get();

        $data = Product::join('variants', 'products.id', 'variants.product_id')
        ->select('products.id','products.productName', 'products.productLabel','products.brand',
            'products.category','products.category_name','products.subcategory','products.subcategory_name',
            'products.type','products.sku','products.barcode','products.supplier',
            'variants.id AS variant_id','variants.variant_name','variants.variant_measurement','variants.variant_description')
        ->where('products.subscriber_id', Auth::user()->subscriber_id)
        ->get();

        return $data;

        // return Product::all();
    }
}
