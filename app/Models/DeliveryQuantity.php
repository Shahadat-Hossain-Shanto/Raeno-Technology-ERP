<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryQuantity extends Model
{
    use HasFactory;

    protected $table = 'delivery_quantity';

    protected $fillable = [
        'order_id',
        'delivery_id',
        'product_name',
        'model',
        'variant',
        'quantity',
    ];
}
