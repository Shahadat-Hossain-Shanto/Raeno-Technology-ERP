<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;
    protected $table = 'store_products'; 
    
    protected $fillable =[
        'productName',
        'productLabel',
        'productCategory',
        'productCategoryName',
        'productSubCategoryName',
        'sku',
        'barcode',
        'supplier',
        'startingStock',
        'safetyStock',
        'color',
        'size',
        'vat',
        'discount',
        'discountType',
        'availableDiscount',
        'offer',
        'availableOffer',
        'shortDiscription',
        'store_name',
        'created_by',
        'updated_by',
        'product_id',
        'brandName',
    ];
    public $timestamps = true;

    
}
