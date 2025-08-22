<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variant extends Model
{
    use HasFactory;
    protected $table = 'variants';

    protected $fillable =[
        'variant_name',
        'variant_measurement',
        'variant_description',
        'variant_image',
        'available_discount',
        'discount_type',
        'discount',
        'taxName',
        'isExcludedTax',
        'tax',
        'product_id',
        'subscriber_id',
    ];
    public $timestamps = true;
}
