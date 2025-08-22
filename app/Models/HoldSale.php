<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoldSale extends Model
{
    use HasFactory;
    protected $table = 'hold_sales'; 
    
    protected $fillable =[
        'reference',
        'client_id',
        'client_name',
        'client_mobile',
        'productId',
        'productName',
        'mrp',
        'quantity',
        'totalPrice',
        'totalDiscount',
        'totalTax',
        'availableOffer',
        'requiredQuantity',
        'grandTotal',
        'discount',
        'offerItemId',
        'offerName',
        'offerQuantity',
        'tax',
        'created_by',
        'updated_by',
        'subscriber_id',
        'pos_id',
        'store_id',
        'user_id',
    ];

    public $timestamps = true;
}
