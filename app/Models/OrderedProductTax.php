<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrderedProduct;

class OrderedProductTax extends Model
{
    use HasFactory;

    protected $table = 'ordered_product_taxes'; 
    
    protected $fillable =[
        'taxName',
        'taxAmount',
        'created_by',
        'updated_by',
        'orderId',
        'productId',
    ];

    public function orderedProduct(){
        return $this->belongsTo(OrderedProduct::class);
    }
}
