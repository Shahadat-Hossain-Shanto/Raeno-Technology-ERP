<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrderedProduct;

class OrderedProductDiscount extends Model
{
    use HasFactory;
    protected $table = 'ordered_product_discounts'; 
    
    protected $fillable =[
        'type',
        'discountAmount',
        'discountName',
        'created_by',
        'updated_by',
        'orderId',
        'productId',

    ];

    public function orderedProduct(){
        return $this->belongsTo(OrderedProduct::class);
    }
}
