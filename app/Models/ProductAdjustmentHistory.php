<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAdjustmentHistory extends Model
{
    use HasFactory;
    protected $table = 'product_Adjustment_histories';

    protected $fillable =[

        'store',
        'store_name',
        'product',
        'product_name',
        'variant_id',
        'variant_name',
        'quantity',
        'product_adjustment_num',
        'date',
        'user_id',
        'subscriber_id',
        'created_by',
        'updated_by',
    ];
    public $timestamps = true;
}
