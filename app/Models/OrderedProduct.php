<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order;
use App\Models\OrderedProductDiscount;
use App\Models\OrderedProductTax;


class OrderedProduct extends Model
{
    use HasFactory;
    protected $table = 'ordered_products'; 
    
    protected $fillable =[
        'productId',
        'productName',
        'quantity',
        'offerItemId',
        'offerQuantity',
        'totalDiscount',
        'totalPrice',
        'grandTotal',
        'totalTax',
        'created_by',
        'updated_by',
        'orderId',

    ];
    public $timestamps = true;

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function orderedProductDiscount(){
        return $this->hasMany(OrderedProductDiscount::class);
    }

    public function orderedProductTax(){
        return $this->hasMany(OrderedProductTax::class);
    }
}
