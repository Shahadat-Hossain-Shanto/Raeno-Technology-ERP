<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturnQuantity extends Model
{
    protected $table = 'product_return_quantity';

    protected $fillable = [
        'order_id',
        'return_id',
        'product_name',
        'model',
        'variant',
        'quantity',
        'rate',
        'amount',
        'rebate',
        'total',
    ];

    public function return()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }
}
