<?php

namespace App\Models;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable =[
        'productName',
        'productLabel',
        'brand',
        'manufacturer',
        'model',
        'category',
        'category_name',
        'subcategory',
        'subcategory_name',
        'type',
        'sku',
        'barcode',
        'supplier',
        'start_stock',
        'safety_stock',
        'color',
        'size',
        'available_discount',
        'discount',
        'discount_type',
        'offerItemId',
        'available_offer',
        'freeItemName',
        'requiredQuantity',
        'freeQuantity',
        'isExcludedTax',
        'taxName',
        'tax',
        'desc',
        'productImage',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
    public $timestamps = true;

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }

    public function inventory(){
        return $this->hasOne(Inventory::class);
    }
    public function brand()
    {
        return $this->hasMany(Brand::class);
    }
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

}
